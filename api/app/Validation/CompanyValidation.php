<?php

namespace App\Validation;

class CompanyValidation
{
    public function getRules(?int $id = null): array
    {
        return [
            'id' => [
                'label' => 'ID',
                'rules' => 'permit_empty|is_natural_no_zero',
            ],
            'name' => [
                'label' => 'Empresa',
                'rules' => 'required|is_unique[companies.name,id,{$id}]|max_length[128]',
                'errors' => [
                    'required' => 'O campo {field} é obrigatório.',
                    'is_unique' => 'O campo {field} deve ser único.',
                    'max_length' => 'O campo {field} deve conter no máximo {param} caracteres.',
                ],
            ],
            'email' => [
                'label' => 'E-mail',
                'rules' => 'required|valid_email|is_unique[companies.email,id,{$id}]|max_length[170]',
                'errors' => [
                    'required' => 'O campo {field} é obrigatório.',
                    'valid_email' => 'O campo {field} deve conter um endereço de e-mail válido.',
                    'is_unique' => 'O campo {field} deve ser único.',
                    'max_length' => 'O campo {field} deve conter no máximo {param} caracteres.',
                ],
            ],
            'address' => [
                'label' => 'Endereço',
                'rules' => 'required|max_length[170]',
                'errors' => [
                    'required' => 'O campo {field} é obrigatório.',
                    'max_length' => 'O campo {field} deve conter no máximo {param} caracteres.',
                ],
            ],
            'phone' => [
                'label' => 'Telefone',
                'rules' => 'required|is_unique[companies.phone,id,{$id}]|max_length[20]',
                'errors' => [
                    'required' => 'O campo {field} é obrigatório.',
                    'is_unique' => 'O campo {field} deve ser único.',
                    'max_length' => 'O campo {field} deve conter no máximo {param} caracteres.',
                ],
            ],
        ];
    }
}
