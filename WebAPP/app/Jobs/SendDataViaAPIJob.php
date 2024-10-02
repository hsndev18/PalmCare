<?php

namespace App\Jobs;

use App\Services\APIService;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Session;

class SendDataViaAPIJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $file_name;
    public $model;

    public function __construct($file_name, $model)
    {
        $this->file_name = $file_name;
        $this->model = $model;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Read the file content
            $contents = Storage::disk('public')->get('uploads/' . $this->file_name);
            $response = (new APIService())->fetchDataToModel($contents, $this->file_name);

            if (isset($response['diagnosis'])) {
                $this->model->update([
                    'disease' => $response['disease'],
                    'diagnosis' => $response['diagnosis'],
                ]);
            } 
        } catch (\Exception $e) {
            Log::error("Error occurred in SendDataViaAPIJob: " . $e->getMessage());
            $this->fail($e);
        }
    }
}
