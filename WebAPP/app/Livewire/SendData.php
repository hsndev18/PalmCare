<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Jobs\SendDataViaAPIJob;
use App\Models\Disease;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class SendData extends Component
{
    use WithFileUploads;

    public $file;
    public $apiResponse = null;
    public $file_name = null;
    public $file_uploaded = false;

    public function save()
    {
        $disk = Storage::disk(config('filesystems.default'));
        if ($this->file) {
            // Save the file to disk and get the file name
            $file = $this->file_name = $this->file->hashName();
            $disk->putFileAs('uploads', $this->file, $file);

            $model = Disease::create([
                'file_name' => $file,
            ]);

            // Dispatch the job
            SendDataViaAPIJob::dispatch($file, $model)->onQueue('send_data');
            $this->file_uploaded = true;
        }
    }

    public function checkResponse()
    {
        $data = Disease::where('file_name', $this->file_name)->first();

        if ($data) {
            if ($data->diagnosis) {
                $this->apiResponse = $data;
                $this->file_uploaded = false;
            }
        }
    }

    public function render()
    {
        return view('livewire.send-data');
    }
}
