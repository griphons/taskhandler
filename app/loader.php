<?php
namespace App;
session_start();

function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Skip comments
        list($name, $value) = explode('=', $line, 2);
        $trimmedValue = trim($value,"\n\r\t\v\0\"");
        putenv(trim($name) . '=' . $trimmedValue);
        $_ENV[trim($name)] = $trimmedValue;
    }
}
loadEnv(__DIR__ . '/../.env');

require_once __DIR__ . "/../app/controllers/HelperClass.php";
require_once __DIR__ . "/../app/controllers/CrudClass.php";
require_once __DIR__ . "/../app/controllers/BaseController.php";
require_once __DIR__ . "/../app/controllers/HomeController.php";
require_once __DIR__ . "/../app/controllers/AdminController.php";
require_once __DIR__ . "/../app/controllers/UserController.php";
require_once __DIR__ . "/../app/controllers/AuthController.php";
require_once __DIR__ . "/../lib/parsedown/Parsedown.php";
