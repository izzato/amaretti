<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Permissions will handle before here
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
            'seo_title' => 'nullable|min:2|max:255',
            'excerpt' => 'nullable|max:5000',
            'slug' => 'nullable|max:255|unique:projects,slug',
            'meta_description' => 'nullable|max:255',
            'meta_keywords' => 'nullable|max:255',
            'featured_image_id' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'content' => 'nullable|max:16000000',
            'published_at' => 'nullable|date'
        ];
    }
}
