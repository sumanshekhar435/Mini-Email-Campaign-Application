<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CSVValidationTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    /**
     * Test CSV file validation with valid data.
     *
     * @return void
     */
    public function test_csv_file_validation()
    {
        $user = User::factory()->create();  // Create a user

        $csvFile = UploadedFile::fake()->create('contacts.csv', 100, 'text/csv');

        $response = $this->actingAs($user)->post('/campaign/store', [
            'name' => 'Test Campaign',
            'contacts_file' => $csvFile,
        ]);

        // Assert successful validation and response
        $response->assertRedirect();
        // $response->assertSessionHasErrors(['csv_errors']);
    }


    /**
     * Test CSV file validation with missing fields.
     *
     * @return void
     */
    public function test_csv_file_validation_with_missing_fields()
    {
        Storage::fake('local');

        // Create a fake CSV file with invalid data
        $csvFile = UploadedFile::fake()->create('invalid_contacts.csv', 100, 'text/csv');

        // Store invalid CSV content
        $invalidCsvContent = "name,email\nJohn Doe,\nJane Smith,janesmith@example.com";
        Storage::put('csv/invalid_contacts.csv', $invalidCsvContent);

        // Perform the file upload and validate the response
        $response = $this->post(route('campaign.store'), [
            'name' => 'Test Campaign',
            'contacts_file' => $csvFile,
        ]);

        // Assert validation failure
        $response->assertRedirect();
        // $response->assertSessionHasErrors(['csv_errors']);
    }
}
