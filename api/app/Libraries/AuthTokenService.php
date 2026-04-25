<?php

namespace App\Libraries;

use App\Models\UserModel;

class AuthTokenService
{
    public function issueToken(array $user): array
    {
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+7 days'));

        $model = new UserModel();
        $model->update($user['id'], [
            'token_hash' => $tokenHash,
            'token_expires_at' => $expiresAt,
        ]);

        $freshUser = $model->find($user['id']);

        return [
            'token' => $token,
            'user' => $freshUser,
        ];
    }

    public function userFromBearer(?string $bearerToken): ?array
    {
        if ($bearerToken === null || $bearerToken === '') {
            return null;
        }

        $tokenHash = hash('sha256', $bearerToken);
        $model = new UserModel();

        $user = $model->where('token_hash', $tokenHash)->first();

        if ($user === null) {
            return null;
        }

        if (!empty($user['token_expires_at']) && strtotime($user['token_expires_at']) < time()) {
            return null;
        }

        return $user;
    }

    public function revokeToken(int $userId): void
    {
        $model = new UserModel();
        $model->update($userId, [
            'token_hash' => null,
            'token_expires_at' => null,
        ]);
    }
}
