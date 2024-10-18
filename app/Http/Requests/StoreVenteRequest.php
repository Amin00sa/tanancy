<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreVenteRequest extends FormRequest
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
    public function rules(Request $request): array
    {
        return [
            'client_id' => ['required'],
            'marche_id' => ['nullable'],
            'objet' => ['required','string'],
            'products' => ['required','array','min:1'],
            'total' => ['required'],
            'taux_tva' => ['required'],
            'date' => ['nullable','date'],
            'status' => ['required','string'],
        ];
    }
}
