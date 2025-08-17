<?php
// app/Http/Requests/StoreCreditRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCreditRequest extends FormRequest
{
    public function authorize()
    {
        return auth::check() && auth::user()->role === 'admin';
    }

    public function rules()
    {
        return [
            'client_name' => 'required|string|max:100|min:2',
            'client_phone' => 'nullable|string|max:20',
            'client_address' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'reason' => 'nullable|string|max:1000'
        ];
    }

    public function messages()
    {
        return [
            'client_name.required' => 'Le nom du client est obligatoire',
            'client_name.min' => 'Le nom doit contenir au moins 2 caractères',
            'client_name.max' => 'Le nom ne peut pas dépasser 100 caractères',
            'client_phone.max' => 'Le téléphone ne peut pas dépasser 20 caractères',
            'amount.required' => 'Le montant est obligatoire',
            'amount.numeric' => 'Le montant doit être un nombre',
            'amount.min' => 'Le montant doit être supérieur à 0',
            'amount.max' => 'Le montant ne peut pas dépasser 999,999.99',
            'reason.max' => 'La raison ne peut pas dépasser 1000 caractères',
            'client_address.max' => 'L\'adresse ne peut pas dépasser 500 caractères'
        ];
    }
}