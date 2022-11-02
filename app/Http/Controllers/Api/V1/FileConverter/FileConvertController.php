<?php

namespace App\Http\Controllers\Api\V1\FileConverter;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileConvertRequest;
use App\Jobs\ConvertFile;
use App\Models\Job;
use App\Services\FileConvertService;
use App\Services\FileConvertService\FileConvertServiceContract;
use Illuminate\Http\Request;

class FileConvertController extends Controller
{
    public function __construct(private FileConvertServiceContract $fileConverter)
    {
    }

    public function convert(FileConvertRequest $request): array
    {
        $validatedFiles = $request->safe()->files;

        $convertToTypes = json_decode($request->convert_to_types, true);

        $job = (FileConvertService::createJob($request))->refresh();

        $files = FileConvertService::uploadFiles($validatedFiles, $convertToTypes, $job);

        ConvertFile::dispatch($files, $job->id, $this->fileConverter);

        return [
            'job_id' => $job->id,
            'job_status' => $job->status->name,
        ];

    }

    public function showAllowedFileFormats(Request $request): \Illuminate\Support\Collection
    {
        return $this->fileConverter->getSupportedOutputFormats($request->query('mime_type'));
    }
}

