<?php

namespace App\Http\Requests;

use App\Enums\Difficulty;
use App\Enums\LinkType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSongRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'artist' => ['nullable', 'string', 'max:255'],
            'genre' => ['nullable', 'string', 'max:100'],
            'key' => ['nullable', 'string', 'max:10'],
            'bpm' => ['nullable', 'integer', 'min:20', 'max:300'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'difficulty' => ['nullable', Rule::enum(Difficulty::class)],
            'tuning' => ['nullable', 'string', 'max:50'],
            'capo' => ['nullable', 'integer', 'min:0', 'max:12'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'is_favorite' => ['boolean'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'links' => ['nullable', 'array'],
            'links.*.type' => ['required_with:links.*', Rule::enum(LinkType::class)],
            'links.*.url' => ['required_with:links.*', 'url', 'max:2048'],
            'links.*.label' => ['nullable', 'string', 'max:255'],
        ];
    }
}
