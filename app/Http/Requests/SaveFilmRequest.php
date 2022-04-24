<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveFilmRequest extends FormRequest
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
        return [
            "title" => "required|max:500",
            "source" => "required|max:255",
            "description" => "required",
            "year" => "required|numeric",
            "rate" => "numeric",
            "country_id" => "required|exists:countries,id",
            "status_id" => "required|exists:statuses,id",
            "genres_ids.*" => "exists:genres,id",
            "cover" => "sometimes|image",
            "torrent" => "sometimes|file",
            "type" => "required|in:film,series"
        ];
    }
}
