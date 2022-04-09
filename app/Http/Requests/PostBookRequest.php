<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostBookRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        // @TODO implement
        return [
            //
            'isbn' => ['required', 'numeric', 'unique:books,isbn'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'max:255'],
            'published_year' => ['required', 'integer', 'between:1900,2020'],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*' => ['required', 'numeric', 'distinct', 'exists:authors,id'],
        ];
    }
}
