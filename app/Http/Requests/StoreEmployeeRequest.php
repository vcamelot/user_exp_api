<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
            'experience' => 'required',
            'experience.*.company_name' => 'required',
            'experience.*.job_title' => 'required',
            'experience.*.experience' => 'required',
            'experience.*.month_from' => 'required|integer|min:1|max:12',
            'experience.*.year_from' => 'required|integer|min:1950|max:2020',
        ];
    }
}
