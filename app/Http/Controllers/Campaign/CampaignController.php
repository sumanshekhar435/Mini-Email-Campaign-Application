<?php

namespace App\Http\Controllers\Campaign;

use Inertia\Inertia;
use App\Models\Contact;
use App\Models\Campaign;
use Illuminate\Http\Request;
use App\Jobs\SendCampaignEmails;
use App\Jobs\SendCampaignEmailsJob;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\CSVValidationService;
use Illuminate\Support\Facades\Validator;

class CampaignController extends Controller
{

    protected $csvValidationService;

    public function __construct(CSVValidationService $csvValidationService)
    {
        $this->csvValidationService = $csvValidationService;
    }

    public function index()
{
    $campaigns = Campaign::withCount('contacts')
        ->withCount(['emailLogs as processed_emails_count' => function ($query) {
            $query->where('status', 'sent');
        }])
        ->orderBy('created_at', 'desc') // Sort by creation date in descending order
        ->paginate(5) // Adjust the number to suit your needs
        ->through(function ($campaign) {
            $campaign->progress = $campaign->contacts_count > 0
                ? ($campaign->processed_emails_count / $campaign->contacts_count) * 100
                : 0;
            return $campaign;
        });

    return Inertia::render('Campaign/index', [
        'campaigns' => $campaigns
    ]);
}

    public function show($id)
    {
        $campaign = Campaign::with('contacts')->findOrFail($id);
        // dd($campaign);
        return Inertia::render('Campaign/show', [
            'campaign' => $campaign,
        ]);
    }

    public function create()
    {
        return inertia('Campaign/create');
    }

    public function store(Request $request)
    {
        // Validate the initial request
        $request->validate([
            'name' => 'required|string|max:255',
            'contacts_file' => 'required|file|mimes:csv,txt',
        ]);

        // Process the CSV file with validation before creating the campaign
        try {
            $this->csvValidationService->validate($request->file('contacts_file'));
        } catch (\Exception $e) {
            // Return errors to the user
            return back()->withErrors(['csv_errors' => $e->getMessage()])->withInput();
        }

        // Create the campaign if CSV validation passes
        $campaign = Campaign::create([
            'name' => $request->name,
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);

        // Process the CSV file and save contacts
        $this->processCSV($request->file('contacts_file'), $campaign);

        // Dispatch a job for further processing
        SendCampaignEmailsJob::dispatch($campaign);

        return redirect()->route('campaign.index')->with('status', 'Campaign is being processed.');
    }

    // protected function validateCSV($file)
    // {
    //     $handle = fopen($file, 'r');
    //     $header = fgetcsv($handle);

    //     $errors = [];
    //     $rowNumber = 1;
    //     while ($row = fgetcsv($handle)) {
    //         $rowNumber++;
    //         $contactData = array_combine($header, $row);

    //         $validator = Validator::make($contactData, [
    //             'name' => 'required|string|max:255',
    //             'email' => 'required|email|max:255',
    //         ]);

    //         if ($validator->fails()) {
    //             $errors[] = "Row $rowNumber: " . implode(', ', $validator->errors()->all());
    //         }
    //     }

    //     fclose($handle);

    //     if (!empty($errors)) {
    //         throw new \Exception(implode('; ', $errors));
    //     }
    // }


    protected function processCSV($file, Campaign $campaign)
    {
        $handle = fopen($file, 'r');
        $header = fgetcsv($handle);

        $contacts = [];
        $totalContacts = 0;

        while ($row = fgetcsv($handle)) {
            $contactData = array_combine($header, $row);

            $contacts[] = [
                'campaign_id' => $campaign->id,
                'name' => $contactData['name'],
                'email' => $contactData['email'],
            ];

            $totalContacts++;
        }

        fclose($handle);

        // Bulk insert contacts
        Contact::insert($contacts);

        // Update campaign with total contacts
        $campaign->update(['total_contacts' => $totalContacts]);
    }
}
