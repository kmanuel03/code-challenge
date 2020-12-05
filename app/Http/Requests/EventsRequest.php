<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class EventsRequest extends FormRequest
{

    public function messages(){
        return [
            'checkedDays.required'    => 'Please select at least one day.'
        ];
    }
    public function rules() {
        return [
            'title' => 'required',
            'dateFrom' => 'required|before:dateTo',
            'dateTo' => 'required',
            'checkedDays' => 'required|array|min:1'
        ];
    }
}
