<?php

namespace App\Jobs;

use App\Models\KycVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessSelfieUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $base64Image;
    protected $filename;
    protected $personalInfo;
    protected $documents;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct($base64Image, $filename, $personalInfo, $documents, $userId)
    {
        $this->base64Image = $base64Image;
        $this->filename = $filename;
        $this->personalInfo = $personalInfo;
        $this->documents = $documents;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Convert base64 to file and store
            $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $this->base64Image));
            $storagePath = 'kyc/selfies/'.$this->filename;

            // Ensure directory exists
            Storage::disk('public')->makeDirectory('kyc/selfies', 0755, true, true);

            // Store the file
            Storage::disk('public')->put($storagePath, $image);

            // Create KYC verification record
            KycVerification::create([
                'user_id' => $this->userId,
                'first_name' => explode(' ', $this->personalInfo['fullName'])[0],
                'last_name' => explode(' ', $this->personalInfo['fullName'])[1] ?? '',
                'date_of_birth' => $this->personalInfo['dob'],
                'bvn' => $this->personalInfo['bvn'] ?? null,
                'address' => $this->personalInfo['address'],
                'document_type' => $this->documents['idType'],
                'nationality' => $this->personalInfo['nationality'],
                'document_number' => $this->documents['idNumber'],
                'document_front_image' => $this->documents['frontId'] ?? null,
                'document_back_image' => $this->documents['backId'] ?? null,
                'selfie_image' => $storagePath,
                'status' => 'pending'
            ]);

            Log::info('KYC selfie processed successfully', [
                'user_id' => $this->userId,
                'storage_path' => $storagePath
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to process selfie upload', [
                'user_id' => $this->userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e; // Re-throw the exception so Laravel knows the job failed
        }
    }
}
