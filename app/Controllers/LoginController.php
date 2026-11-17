<?php

namespace App\Controllers;

use App\Models\MMember;
use App\Models\MLogin;

class LoginController extends RestfulController
{
    public function login()
    {
        $data = $this->request->getJSON(true);

        if (!is_array($data)) {
            return $this->responseHasil(
                400, false, "Format JSON tidak valid"
            );
        }

        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            return $this->responseHasil(
                400, false, "Email dan password wajib diisi"
            );
        }

        $model = new MMember();
        $member = $model->where('email', $email)->first();

        if (!$member || !password_verify($password, $member['password'])) {
            return $this->responseHasil(
                401, false, "Email atau password salah"
            );
        }

        $token = bin2hex(random_bytes(32));

        $login = new MLogin();
        $login->insert([
            'member_id' => $member['id'],
            'auth_key' => hash('sha256', $token)
        ]);

        return $this->responseHasil(200, true, [
            'token' => $token,
            'user' => [
                'id' => (int) $member['id'],
                'email' => $member['email']
            ]
        ]);
    }
}
