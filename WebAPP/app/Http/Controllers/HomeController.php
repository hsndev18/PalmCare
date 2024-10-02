<?php

namespace App\Http\Controllers;

use App\Jobs\SendDataViaAPIJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function sendData(Request $request)
    {
        $disk = Storage::disk(config('filesystems.default'));
        if ($request->hasFile('file')) {

            $file = $request->file('file')->hashName();
            $disk->putFileAs('uploads', $request->file('file'), $file);

            SendDataViaAPIJob::dispatch($file)->onQueue('send_data');
        }
    }
}
