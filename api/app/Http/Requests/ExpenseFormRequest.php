<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'descriptions' => 'required|string|max:250',
            'price' => 'required|numeric',
            'expense_date' => 'required|date_format:d/m/Y',
        ];
    }
}
