<?php

namespace App\Controllers\Api\V1;

use App\Models\UserModel;
use App\Validation\RegisterValidation;
use CodeIgniter\RESTful\ResourceController;

class RegisterController extends ResourceController
{
    protected $modelName = UserModel::class;
    protected $format    = 'json';

    public function create()
    {
        $rules = (new RegisterValidation())->getRules();

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $payload = $this->request->getJSON(true) ?? [];
        $payload['password'] = $payload['password'] ?? '';

        $userId = $this->model->insert([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => $payload['password'],
            'role' => $payload['role'],
        ]);

        $user = $this->model->find($userId);
        $authData = service('authTokenService')->issueToken($user);

        return $this->respondCreated([
            'token' => $authData['token'],
            'user' => $authData['user'],
        ], 'Usuario criado com sucesso.');
    }
}
