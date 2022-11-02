<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'job_code' => $this->code,
            'status' => ucfirst(strtolower($this->status->name)),
            'created_at' => $this->created_at->diffForHumans(),
            'no_of_medias' => $this->media_count,
            'downloadable_file_name' => $this->downloadableFileName,
        ];
    }
}
