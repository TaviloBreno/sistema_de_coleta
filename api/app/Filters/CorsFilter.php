<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CorsFilter implements FilterInterface
{
    private array $allowedOrigins = [
        'http://localhost:5173',
        'http://127.0.0.1:5173',
        'http://localhost:4173',
        'http://127.0.0.1:4173',
    ];

    public function before(RequestInterface $request, $arguments = null)
    {
        $origin = $request->getHeaderLine('Origin');

        if ($this->isAllowedOrigin($origin)) {
            header("Access-Control-Allow-Origin: {$origin}");
            header('Vary: Origin');
        }

        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Max-Age: 86400');

        if (strtoupper($request->getMethod()) === 'OPTIONS') {
            return service('response')->setStatusCode(ResponseInterface::HTTP_NO_CONTENT);
        }

        return null;
    }

    private function isAllowedOrigin(string $origin): bool
    {
        if ($origin === '') {
            return false;
        }

        if (in_array($origin, $this->allowedOrigins, true)) {
            return true;
        }

        $parts = parse_url($origin);

        if ($parts === false || !isset($parts['scheme'], $parts['host'])) {
            return false;
        }

        if (!in_array($parts['scheme'], ['http', 'https'], true)) {
            return false;
        }

        return in_array($parts['host'], ['localhost', '127.0.0.1'], true);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
