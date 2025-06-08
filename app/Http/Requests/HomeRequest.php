<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\SortOrder;
use Illuminate\Foundation\Http\FormRequest;

class HomeRequest extends FormRequest
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
            'sort' => ['nullable', 'string', 'in:'.implode(',', SortOrder::all())],
            'search' => ['nullable', 'string'],
        ];
    }

    // public function validatedSort(): string
    // {
    //     return $this->input('sort', SortOrder::default());
    // }
}
