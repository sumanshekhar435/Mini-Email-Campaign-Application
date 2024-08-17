<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class CSVValidationService
{
    public function validate(UploadedFile $file)
    {
        // Open the file for reading
        $handle = fopen($file->getRealPath(), 'r');
        if ($handle === false) {
            throw new \Exception('Unable to open file.');
        }

        // Read and debug the header
        $header = fgetcsv($handle);
        
        // Print the header for debugging
        if ($header === false) {
            fclose($handle);
            throw new \Exception('Unable to read CSV header.');
        }

        // Output header to log
        error_log('CSV Header: ' . implode(', ', $header));

        // Validate header structure
        if (!in_array('name', $header) || !in_array('email', $header)) {
            fclose($handle);
            throw new \Exception('CSV file must contain "name" and "email" headers.');
        }

        $errors = [];
        $rowNumber = 1;
        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;
            $contactData = array_combine($header, $row);

            // Output row data to log
            if ($contactData === false) {
                $errors[] = "Row $rowNumber: Incorrect number of columns.";
                continue;
            }

            error_log('Row Data: ' . implode(', ', $contactData));

            $validator = Validator::make($contactData, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ]);

            if ($validator->fails()) {
                $errors[] = "Row $rowNumber: " . implode(', ', $validator->errors()->all());
            }
        }

        fclose($handle);

        if (!empty($errors)) {
            throw new \Exception(implode('; ', $errors));
        }
    }
}
