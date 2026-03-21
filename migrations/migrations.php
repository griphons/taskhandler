<?php
require_once __DIR__ . "/../app/loader.php";
use App\Controllers\CRUD;

$pdo = new CRUD();
// Create tables
$pdo->run("
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    created_at DATETIME NOT NULL
);
");
$pdo->run("
CREATE TABLE IF NOT EXISTS tasks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    slug TEXT NOT NULL,
    body TEXT NOT NULL,
    due_date DATE NOT NULL,
    status TINYINT(2) DEFAULT 0,
    user_id INTEGER NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
);
");

/*
// Seed an admin user if none exist
$exists = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
if ($exists === 0) {
    $username = 'admin';
    $passwordHash = password_hash('change-me-now', PASSWORD_DEFAULT);
    $now = gmdate('c');
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, created_at) VALUES (:u, :p, :c)");
    $stmt->execute([':u' => $username, ':p' => $passwordHash, ':c' => $now]);
    echo "Seeded admin user (username: admin, password: change-me-now). Please change this in production!\n";
} else {
    echo "Users table already seeded.\n";
}
// Optionally seed a sample post
$posts = (int)$pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
if ($posts === 0) {
    $now = gmdate('c');
    $stmt = $pdo->prepare("
        INSERT INTO posts (title, slug, body, created_at, updated_at)
        VALUES (:t, :s, :b, :c, :u)
    ");
    $stmt->execute([
        ':t' => 'Hello, world!',
        ':s' => 'hello-world',
        ':b' => 'This is your first blog post in pure PHP. Edit or delete me from the admin.',
        ':c' => $now,
        ':u' => $now
    ]);
    echo "Seeded a sample post.\n";
}*/