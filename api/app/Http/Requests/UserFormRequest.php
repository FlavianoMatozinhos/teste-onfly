<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Pode ser ajustado conforme suas regras de autorização
    }

    public function rules()
    {
        $userId = $this->route('user') ?? null; // Obtém o ID do usuário da rota, se existir

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'password' => 'sometimes|string|min:8',
        ];
    }
}
