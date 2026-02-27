<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;

final class AuthController
{
    public function showLogin(?string $error = null): void
    {
        view('auth/login', ['title' => 'Login', 'error' => $error]);
    }

    public function login(): void
    {
        verify_csrf();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $this->showLogin('Email and password are required.');
            return;
        }

        if (!Auth::attempt($email, $password)) {
            $this->showLogin('Invalid credentials.');
            return;
        }

        redirect('/dashboard');
    }

    public function logout(): void
    {
        verify_csrf();
        Auth::logout();
        redirect('/login');
    }
}
