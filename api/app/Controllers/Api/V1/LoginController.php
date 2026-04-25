<?php

namespace App\Controllers\Api\V1;

use App\Models\UserModel;
use App\Validation\LoginValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LoginController extends ResourceController
{
    protected $modelName = UserModel::class;
    protected $format    = 'json';

    public function create()
    {
        $rules = (new LoginValidation())->getRules();

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $payload = $this->request->getJSON(true) ?? [];
        $user = $this->model->where('email', $payload['email'])->first();

        if ($user === null || !password_verify($payload['password'], $user['password'])) {
            return $this->failUnauthorized('Credenciais invalidas.');
        }

        $authData = service('authTokenService')->issueToken($user);

        return $this->respond([
            'token' => $authData['token'],
            'user' => $authData['user'],
        ], ResponseInterface::HTTP_OK, 'Login realizado com sucesso.');
    }
}
