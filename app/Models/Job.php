<?php

namespace App\Models;

use App\Enums\Job\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Job extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $casts = [
        'status' => Status::class,
    ];

    protected $fillable = [
        'status',
        'code',
    ];

    protected $appends = [
        'downloadable_file_name',
    ];

    // Accessors and Mutators
    public function downloadableFileName(): Attribute
    {
        return new Attribute(
            get: function () {
                if ($this->media_count === 1) {
                    return $this->media[0]->file_name;
                }

                return $this->code.'.zip';
            }
        );
    }
}
