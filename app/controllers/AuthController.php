<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Login form
     * @return void
     */
    public function index(): void {
        $title = 'Sign In';
        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/login.php';
        include __DIR__ . '/../views/footer.php';
    }

    /**
     * Submit Login
     * @return void
     */
    public function login(): void {
        $name = trim($_POST['name'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = $this->crud->read()
        ->table('users')
        ->where('name', '','=',$name)
        ->get();

        if (!$user || !password_verify($password, $user["password"])) {
            $title = 'Login';
            $error = 'Invalid credentials.';
            include __DIR__ . '/../views/header.php';
            include __DIR__ . '/../views/login.php';
            include __DIR__ . '/../views/footer.php';
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['username'] = $user['username'];
        if ($user["is_admin"]) {
            header('Location: /admin');
        } else {
            header('Location: /');
        }
        exit;
    }

    /**
     * Submit logout
     * @return void
     */
    public function logout(): void {
        // Keep CSRF token? Optional. Simplest: destroy everything.
        session_destroy();
        header('Location: /');
        exit;
    }

}