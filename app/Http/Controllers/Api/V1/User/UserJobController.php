<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConvertFileRequest;
use App\Http\Resources\JobResource;
use App\Models\Job;
use App\Models\User;
use Spatie\MediaLibrary\Support\MediaStream;

class UserJobController extends Controller
{
    public function index(User $user)
    {
        $this->authorize('viewAny', $user);

        $jobs = $user->jobs()
            ->with('media:id,model_id,model_type,disk,file_name')
            ->withCount('media')
            ->latest()
            ->get();

        return JobResource::collection($jobs);
    }

    public function download(Job $job)
    {
        $this->authorize('download', $job);

        $medias = $job->getMedia();

        if ($medias->count() === 1) {
            return response()->download($medias[0]->getPath(), $medias[0]->file_name);
        }

        return MediaStream::create($job->code.'.zip')->addMedia($medias);
    }

}
