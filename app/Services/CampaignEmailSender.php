<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\Campaign;
use App\Mail\CampaignMail;
use App\Models\CampaignEmailLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CampaignEmailSender
{
    protected $campaign;
    protected $batchSize = 50;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function sendEmails()
    {
        $totalContacts = Contact::where('campaign_id', $this->campaign->id)->count();
        $this->campaign->update(['total_contacts' => $totalContacts]);

        Contact::where('campaign_id', $this->campaign->id)
            ->chunk($this->batchSize, function ($contacts) {
                $this->processBatch($contacts);
            });

        // Check if all contacts are processed and update campaign status
        if ($this->campaign->total_contacts == $this->campaign->processed_contacts) {
            $this->campaign->update(['status' => 'completed']);
            // Notify the campaign owner that the campaign has been processed
            $this->campaign->user->notify(new \App\Notifications\CampaignProcessed($this->campaign));
        }

        // Update aggregated counts from logs
        $this->updateCampaignStats();
    }

    protected function processBatch($contacts)
    {
        foreach ($contacts as $contact) {
            try {
                Mail::to($contact->email)->send(new CampaignMail($contact, $this->campaign));
                // Update contact status to 'sent' after successful email
                $contact->update(['status' => 'sent']);
                CampaignEmailLog::create([
                    'campaign_id' => $this->campaign->id,
                    'contact_id' => $contact->id,
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            } catch (\Exception $e) {
                 // Update contact status to 'failed' or 'failed'
                 $contact->update(['status' => 'failed']);
                CampaignEmailLog::create([
                    'campaign_id' => $this->campaign->id,
                    'contact_id' => $contact->id,
                    'status' => 'failed',
                ]);

                Log::error('Email sending failed', [
                    'contact_id' => $contact->id,
                    'error' => $e->getMessage()
                ]); // Log detailed error
            }
        }

        $this->campaign->increment('processed_contacts', $contacts->count());
    }

    protected function updateCampaignStats()
    {
        $sentCount = CampaignEmailLog::where('campaign_id', $this->campaign->id)
            ->where('status', 'sent')
            ->count();

        $failedCount = CampaignEmailLog::where('campaign_id', $this->campaign->id)
            ->where('status', 'failed')
            ->count();

        $inProgressCount = CampaignEmailLog::where('campaign_id', $this->campaign->id)
            ->where('status', 'in_progress')
            ->count();

        // Log the counts for debugging
        Log::info('Campaign Stats Update:', [
            'sent' => $sentCount,
            'failed' => $failedCount,
            'in_progress' => $inProgressCount,
        ]);

        $this->campaign->update([
            'sent_emails' => $sentCount,
            'failed_emails' => $failedCount,
            'in_progress_emails' => $inProgressCount,
        ]);
    }
}
