<?php

namespace App\Controllers;

class HelperClass
{
    /** Escape output for HTML */
    public function h(string $value): string {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /** Redirect and stop execution */
    public function redirect(string $path): never {
        header('Location: ' . $path);
        exit;
    }

    /** Generate or reuse a CSRF token stored in the session */
    public function csrf_token(): string {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf'];
    }

    /** Validate CSRF token for POST requests */
    public function verify_csrf() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf'] ?? '';
            if (!hash_equals($_SESSION['csrf'] ?? '', $token)) {
                http_response_code(419);
                exit('Invalid CSRF token. Please go back and try again.');
            }
        }
    }

    /** Simple auth helpers */
    public function is_logged_in(): bool {
        return isset($_SESSION['user_id']);
    }

    public function require_login(): void {
        if (!$this->is_logged_in()) $this->redirect('/login');
    }

    /** Generate a URL-friendly slug */
    public function slugify(string $title): string {
        $slug = preg_replace('~[^\pL\d]+~u', '-', $title);
        $slug = trim($slug, '-');
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);
        $slug = strtolower($slug);
        $slug = preg_replace('~[^-\w]+~', '', $slug) ?? '';
        return $slug !== '' ? $slug : bin2hex(random_bytes(4));
    }

}