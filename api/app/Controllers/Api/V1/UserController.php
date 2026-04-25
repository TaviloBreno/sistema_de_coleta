<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class UserController extends ResourceController
{
    public function index()
    {
        return $this->respond(service('authContext')->user());
    }

    public function logout()
    {
        $user = service('authContext')->user();

        if ($user === null) {
            return $this->failUnauthorized('Nao autorizado.');
        }

        service('authTokenService')->revokeToken((int) $user['id']);
        service('authContext')->setUser(null);

        return $this->respond(['message' => 'Logout realizado com sucesso.'], ResponseInterface::HTTP_OK);
    }
}
