<?php

namespace App\Services\FileConvertService;

use Illuminate\Support\Collection;

interface FileConvertServiceContract
{
    public function convertFiles(array $files, int $tagId): void;
    public function getSupportedOutputFormats(string $inputFormat): Collection;

}