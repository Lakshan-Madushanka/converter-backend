<?php

namespace App\Http\Requests;

use App\Rules\ConverterPayloadValidator;
use App\Rules\UploadedFileTypeToConvert;
use Illuminate\Foundation\Http\FormRequest;

class FileConvertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'convert_to_types.*' => [
                'bail',
                'required',
            ],

            'files.*' => [
                'bail',
                'file',
                'required',
                new ConverterPayloadValidator(),
                'max:10000',
            ],
        ];
    }
}
