<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Proposal;

class UpdateProposalRequest extends FormRequest
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
            'title' => 'required|min:2|max:255',
            'slug' => 'nullable|max:255|unique:proposals,slug,' . $this->route()->parameter('proposal')->getKey(),
            'pdf' => 'nullable|file|mimes:pdf|max:64000',
            'published_at' => 'nullable|date'
        ];
    }
}
