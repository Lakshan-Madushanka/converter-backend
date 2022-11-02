<?php

namespace App\Rules;

use App\Services\FileConvertService\CloudConverterService;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use function request;

class ConverterPayloadValidator implements Rule
{
    private $convertToTypeNotSupported = false;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  UploadedFile  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $convertToType = json_decode(request()->input('convert_to_types'), true);

        $originalFileName = $value->getClientOriginalName();

        if (array_key_exists($originalFileName, $convertToType)) {

            $convertToType = $convertToType[$originalFileName];
            $uploadedFileType = Str::lower($value->getClientOriginalExtension());

            /**
             * @var Collection
             */
            $supportedTypes = app(CloudConverterService::class)->getSupportedOutputFormats($uploadedFileType);

            if ($supportedTypes->isEmpty()) {
                return false;
            }

            if (!$supportedTypes->contains($convertToType)) {
                $this->convertToTypeNotSupported = true;

                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $message = 'The :attribute file type is not supported.';

        if ($this->convertToTypeNotSupported) {
            $message = 'The :attribute conversion file type is not supported.';
        }

        return $message;
    }

}
