<?php

namespace App\Services\FileConvertService;

use CloudConvert\Laravel\Facades\CloudConvert;
use CloudConvert\Models\Job;
use CloudConvert\Models\Task;
use CloudConvert\Models\TaskCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class CloudConverterService implements FileConvertServiceContract
{
    public const URL = "https://api.cloudconvert.com/v2/convert/";

    public function convertFiles(array $files, int $tagId): void
    {
        $job = $this->initJobInstance($tagId);

        $this->addTasks($job, $files, $tagId);

         $this->createJob($job);

        $this->uploadFiles($job->getTasks(), $files);

    }

    public function initJobInstance(int $tagId): Job
    {
        return (new Job())->setTag($tagId);
    }

    public function addTasks(Job $job, array $files, $tagName): void
    {
        foreach ($files as $file) {
            $job->addTask(new Task('import/upload', 'upload-my-file'.$file['id']))
                ->addTask(
                    (new Task('convert', 'convert-my-file'.$file['id']))
                        ->set('input', 'upload-my-file'.$file['id'])
                        ->set('output_format', $file['convertToType'])
                )
                ->addTask(
                    (new Task('export/url', 'export-my-file'.$file['id']))
                        ->set('input', 'convert-my-file'.$file['id'])
                );
        }

    }

    public function createJob(Job $job): void
    {
        CloudConvert::jobs()->create($job);
    }

    public function uploadFiles(TaskCollection $tasks, array $files): void
    {
        foreach ($files as $file) {
            $uploadTask = $tasks->whereName('upload-my-file'.$file['id'])[0];

            $inputStream = fopen($file['path'], 'rb');

            CloudConvert::tasks()->upload($uploadTask, $inputStream);
        }
    }

    public function getSupportedOutputFormats(string $inputFormat): Collection
    {
        $query = http_build_query([
           'filter[input_format]' => $inputFormat,
        ]);

        $url = self::URL . 'formats?' . $query;

       return Http::timeout(10)->get($url)->collect('data')->pluck('output_format');
    }
}