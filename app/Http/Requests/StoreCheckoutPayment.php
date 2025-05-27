<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutPayment extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name'             => 'required|string|max:255',
            'last_name'              => 'required|string|max:255',
            'identification_number' => 'required|string|max:20|min:11|unique:clients,identification_number',
            'email'                 => 'required|email|max:255|unique:clients,email',

            'country'               => 'required|string|max:100',
            'state'                 => 'required|string|max:100',
            'city'                  => 'required|string|max:100',
            'address'               => 'required|string|max:255',
            'zipCode'           => 'required|string|max:20',
        ];
    }
    public function messages(): array
    {
        return [
            'firstname.required' => 'O campo nome é obrigatório.',
            'firstname.max' => 'O campo nome não pode ter mais que 255 caracteres.',
            'lastname.required' => 'O campo sobrenome é obrigatório.',
            'lastname.max' => 'O campo sobrenome não pode ter mais que 255 caracteres.',
            'identification_number.required' => 'O campo número de identificação é obrigatório.',
            'identification_number.string' => 'O campo número de identificação deve ser válido.',
            'identification_number.max' => 'O número de identificação não pode ter mais que 11 caracteres.',
            'identification_number.min' => 'O número de identificação não pode ter menos que 11 caracteres.',
            'identification_number.unique' => 'Este número de identificação já está em uso.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.max' => 'O e-mail não pode ter mais que 255 caracteres.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'country.required' => 'O país é obrigatório.',
            'state.required' => 'O estado é obrigatório.',
            'city.required' => 'A cidade é obrigatória.',
            'address.required' => 'O endereço é obrigatório.',
            'zipCode.required' => 'O código postal é obrigatório.',
        ];
    }
}
