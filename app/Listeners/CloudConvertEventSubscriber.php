<?php

namespace App\Listeners;

use App\Enums\Job\Status;
use App\Events\ConversionJobFailed;
use App\Events\ConversionJobSucceeded;
use App\Models\Job;
use CloudConvert\Laravel\Facades\CloudConvert;
use CloudConvert\Models\WebhookEvent;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CloudConvertEventSubscriber
{
    public function onJobFinished(WebhookEvent $event): void
    {
        $convertedJob = $event->getJob();

        if (is_null($convertedJob)) {
            return;
        }

        $localJob = Job::find($convertedJob->getTag());
        $inputMedias = $localJob->getMedia();

        // Replace local files with corresponded converted files
        foreach (($convertedFileUrls = $convertedJob->getExportUrls()) as $file) {

            $source = CloudConvert::getHttpTransport()->download($file->url)->detach();

            foreach ($inputMedias as $media) {
                $mediaFileNameWithoutExtension = explode('.', $media->name)[0];

                $convertedFileNameArray = explode('.', $file->filename);
                $convertedFileNameWithoutExtension = $convertedFileNameArray[0];
                $convertedFileExtension = $convertedFileNameArray[1];

                $mediaPathWithoutExtension = explode('.', $media->getPath())[0];
                $pathToMatch = $mediaPathWithoutExtension.'.*';

                if ($mediaFileNameWithoutExtension === $convertedFileNameWithoutExtension) {
                    if (File::delete(File::glob($pathToMatch))) {

                        $destinationPath = $mediaPathWithoutExtension.'.'.$convertedFileExtension;
                        $destination = fopen($destinationPath, 'wb');

                        stream_copy_to_stream($source, $destination);

                        $media->update(['file_name' => $file->filename]);
                    }
                }
            }
        }

        $downloadFileName = $localJob->code.'.zip';

        if (count($convertedFileUrls) === 1) {
            $downloadFileName = $convertedFileUrls[0]->filename;
        }

        $localJob->update(['status' => Status::SUCCESS]);

        ConversionJobSucceeded::dispatch($localJob->id, $downloadFileName);
    }

    public function onJobFailed(WebhookEvent $event): void
    {
        Log::error('Job failed', (array) $event->getJob());

        $convertedJob = $event->getJob();

        if (is_null($convertedJob)) {
            return;
        }

        $localJob = Job::find($convertedJob->getTag());

        $localJob->update(['status' => Status::ERROR]);

        ConversionJobFailed::dispatch($localJob->id);
    }

    public function subscribe($events): void
    {
        $events->listen(
            'cloudconvert-webhooks::job.finished',
            [__CLASS__, 'onJobFinished']
        );

        $events->listen(
            'cloudconvert-webhooks::job.failed',
            [__CLASS__, 'onJobFailed']
        );
    }
}
