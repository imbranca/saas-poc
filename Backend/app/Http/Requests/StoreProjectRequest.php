<?php

namespace App\Http\Requests;

use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'name'=>'required|string',
            'description'=>['nullable','string'],
            'status'=>['required','string',Rule::enum(ProjectStatus::class)]
        ];
    }

    protected function failedValidation(Validator $validator)
  {
      throw new HttpResponseException(
          response()->json([
              'message' => 'Validation failed',
              'errors' => $validator->errors()
          ], Response::HTTP_BAD_REQUEST)
      );
  }
}
