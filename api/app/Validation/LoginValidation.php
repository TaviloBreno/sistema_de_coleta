<?php

namespace App\Validation;

class LoginValidation
{
    public function getRules(): array
    {
        return [
            'email' => [
                'label' => 'E-mail',
                'rules' => 'required|valid_email',
            ],
            'password' => [
                'label' => 'Senha',
                'rules' => 'required',
            ],
        ];
    }
}
