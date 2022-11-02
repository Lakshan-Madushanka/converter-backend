<?php

namespace App\Services;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileConvertService
{
    public static function createJob(Request $request): Job
    {
        return $request->user()->jobs()->create([
            'code' => Str::random(),
        ]);
    }

    public static function uploadFiles(array $validatedFiles, array $convertToTypes, Job $job): array
    {
        $files = [];

        foreach ($validatedFiles as $uploadedFile) {
            $media = $job->addMedia($uploadedFile)
                ->toMediaCollection();

            $file = [
                'id' => Str::random(),
                'convertToType' => $convertToTypes[$uploadedFile->getClientOriginalName()],
                'path' => $media->getPath(),
            ];

            $files[] = $file;
        }

        return $files;
    }
}