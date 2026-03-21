<?php
require_once __DIR__ . "/../app/loader.php";
use App\Controllers\CRUD;

$pdo = new CRUD();

/* Seed users */

$user = [
    'name' => 'admin',
    'password' => password_hash('admin123', PASSWORD_DEFAULT),
    'is_admin' => 1,
    'created_at' => gmdate('Y-m-d H:i:s'),
];
$pdo->insert($user)->table('users')->get();


$user = [
    'name' => 'user1',
    'password' => password_hash('user1', PASSWORD_DEFAULT),
    'is_admin' => 0,
    'created_at' => gmdate('Y-m-d H:i:s'),
];
$pdo->insert($user)->table('users')->get();
$user = [
    'name' => 'user2',
    'password' => password_hash('user2', PASSWORD_DEFAULT),
    'is_admin' => 0,
    'created_at' => gmdate('Y-m-d H:i:s'),
];
$pdo->insert($user)->table('users')->get();
$user = [
    'name' => 'user3',
    'password' => password_hash('user3', PASSWORD_DEFAULT),
    'is_admin' => 0,
    'created_at' => gmdate('Y-m-d H:i:s'),
];
$pdo->insert($user)->table('users')->get();

/* Seed Example Tasks */

for($num = 1; $num <= 10; $num++) {
    $random_user = rand(2, 4);
    $status = rand(0, 2);
    $day = rand(1, 20) - 10;
    $dayMod = $day < 0 ? (string) $day : ($day == 0 ? "+1" : "+" . $day);
    $due_date = gmdate('Y-m-d', strtotime($dayMod." day"));

    $task = [
        'name' => 'Task No.' . $num,
        'slug' => 'task-no' . $num,
        'user_id' => $random_user,
        'body' => "**Task No.$num Description**\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent orci enim, accumsan ut fringilla sed, imperdiet sit amet nisi. Ut in magna et felis vehicula efficitur eu ut ex.",
        'due_date' => $due_date,
        'status' => $status,
        'created_at' => gmdate('Y-m-d H:i:s'),
        'updated_at' => gmdate('Y-m-d H:i:s')
    ];
    $pdo->insert($task)->table('tasks')->get();
}

