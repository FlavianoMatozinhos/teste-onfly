<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="ExpenseFormRequest",
 *     required={"descriptions", "price", "expense_date"},
 *     @OA\Property(property="descriptions", type="string", example="Expense description"),
 *     @OA\Property(property="price", type="number", format="float", example=100.50),
 *     @OA\Property(property="expense_date", type="string", format="date", example="2024-07-05"),
 * )
*/
class ExpenseFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'descriptions' => 'required|string|max:191',
            'price' => 'required|numeric',
            'expense_date' => 'required|date_format:d/m/Y',
        ];
    }
}
