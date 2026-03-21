<?php

use App\Controllers\HomeController;
use App\Controllers\AdminController;
use App\Controllers\UserController;
use App\Controllers\TaskController;
use App\Controllers\AuthController;
use App\Controllers\HelperClass;

$helper = new HelperClass();

// A tiny router: normalize path and dispatch
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
if ($uri !== '/' && str_ends_with($uri, '/')) $uri = rtrim($uri, '/');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
switch (true) {
    case $uri === '/' && $method === 'GET':
        $helper->redirect('/tasks/pending');
    case preg_match('#^/tasks/*([\w-]*)$#', $uri, $m) && $method === 'GET':
        new HomeController()->index($m[1]);
        break;
    case preg_match('#^/task/([\w-]+)$#', $uri, $m) && $method === 'GET':
        new HomeController()->task($m[1]);
        break;
    case $uri === '/login' && $method === 'GET':
        new AuthController()->index();
        break;
    case $uri === '/login' && $method === 'POST':
        $helper->verify_csrf();
        new AuthController()->login();
        break;
    case $uri === '/logout' && $method === 'POST':
        $helper->verify_csrf();
        new AuthController()->logout();
        break;
    case $uri === '/admin' && $method === 'GET':
        new AdminController()->index();
        break;
    case $uri === '/user-list' && $method === 'GET':
        new UserController()->index();
        break;
    case $uri === '/user-add' && $method === 'GET':
        new UserController()->create();
        break;
    case preg_match('#^/user-edit/([\w-]+)$#', $uri, $m) && $method === 'GET':
        new UserController()->update($m[1]);
        break;
    case $uri === '/user-submit' && $method === 'POST':
        $helper->verify_csrf();
        new UserController()->submit();
        break;
    case preg_match('#^/user-delete/([\w-]+)$#', $uri, $m) && $method === 'POST':
        $helper->verify_csrf();
        new UserController()->delete($m[1]);
        break;
    case $uri === '/task-list' && $method === 'GET':
        new TaskController()->index();
        break;
    case preg_match('#^/task-view/([\w-]+)$#', $uri, $m) && $method === 'GET':
        new TaskController()->view($m[1]);
        break;
    case $uri === '/task-add' && $method === 'GET':
        new TaskController()->create();
        break;
    case preg_match('#^/task-edit/([\w-]+)$#', $uri, $m) && $method === 'GET':
        new TaskController()->update($m[1]);
        break;
    case $uri === '/task-submit' && $method === 'POST':
        $helper->verify_csrf();
        new TaskController()->submit();
        break;
    case preg_match('#^/task-delete/([\w-]+)$#', $uri, $m) && $method === 'POST':
        $helper->verify_csrf();
        new TaskController()->delete($m[1]);
        break;
    default:
        new HomeController()->error404();
        break;
}