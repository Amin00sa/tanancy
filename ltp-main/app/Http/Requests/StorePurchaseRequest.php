<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'marche_id' => ['required'],
            'supplier_id' => ['required'],
            'bank_id' => ['required'],
            'products' => ['required','array','min:1'],
            'taux_tva' => ['required'],
            'echeance_date' => ['nullable'],
            'date' => ['required'],
            'total' => ['required'],
            'paiement_immediat' => ['required'],
            'type' => ['required'],
            'proof_file' => ['nullable','mimes:jpeg,jpg,png,gif,pdf'],
            'motif' => ['nullable'],
        ];
    }
}
