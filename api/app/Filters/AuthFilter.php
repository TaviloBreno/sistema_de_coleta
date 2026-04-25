<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeaderLine('Authorization');

        if (!preg_match('/^Bearer\s+(.*)$/i', $header, $matches)) {
            return service('response')->setJSON(['message' => 'Nao autorizado.'])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $token = trim($matches[1]);
        $user = service('authTokenService')->userFromBearer($token);

        if ($user === null) {
            return service('response')->setJSON(['message' => 'Token invalido ou expirado.'])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        service('authContext')->setUser($user);

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
