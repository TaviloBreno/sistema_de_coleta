<?php

namespace App\Libraries;

class AuthContext
{
    private ?array $user = null;

    public function setUser(?array $user): void
    {
        $this->user = $user;
    }

    public function user(): ?array
    {
        return $this->user;
    }

    public function userId(): ?int
    {
        return $this->user['id'] ?? null;
    }

    public function role(): ?string
    {
        return $this->user['role'] ?? null;
    }
}
