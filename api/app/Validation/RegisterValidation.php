<?php

namespace App\Validation;

class RegisterValidation
{
    public function getRules(): array
    {
        return [
            'name' => [
                'label' => 'Nome',
                'rules' => 'required|max_length[120]',
            ],
            'email' => [
                'label' => 'E-mail',
                'rules' => 'required|valid_email|is_unique[users.email]|max_length[170]',
            ],
            'password' => [
                'label' => 'Senha',
                'rules' => 'required|min_length[6]',
            ],
            'password_confirmation' => [
                'label' => 'Confirmacao de senha',
                'rules' => 'required|matches[password]',
            ],
            'role' => [
                'label' => 'Perfil',
                'rules' => 'required|in_list[admin,manager,user]',
            ],
        ];
    }
}
