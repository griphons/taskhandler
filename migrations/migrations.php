<?php
require_once __DIR__ . "/../app/loader.php";
use App\Controllers\CRUD;

$pdo = new CRUD();

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
