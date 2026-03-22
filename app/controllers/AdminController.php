<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        if(!$this->helper->is_logged_in() || !$this->user['is_admin']){
            $this->helper->redirect('/');
        }
    }

    /**
     * Admin Dashboard
     * @return void
     */
    public function index(): void
    {
        $title = 'Admin Dashboard';
        $data = [
            "status" => [],
            "user" => [],
        ];

        $statusKeys = array_keys($this->taskStatus);
        $data["status"]["all"] = 0;
        foreach($statusKeys as $key) { $data["status"][$key] = 0; }
        $tasks = $this->crud->reads(["status","due_date"])
            ->table('tasks')->get();
        foreach($tasks as $task) {
            if($task["status"] == 0) {
                if($task["due_date"] < date('Y-m-d')) {
                    $data["status"]["missed"]++;
                } else {
                    $data["status"]["pending"]++;
                }
            } else {
                $data["status"][$statusKeys[$task["status"]]]++;
            }
        }
        $data['status']["all"] = count($tasks);

        $users = $this->crud->reads(["id"])
            ->table('users')
            ->where('is_admin', '','!=',1)
            ->get();
        $admins = $this->crud->reads(["id"])
            ->table('users')
            ->where('is_admin', '','=',1)
            ->get();
        $data["user"]["all"] = count($users) + count($admins);
        $data["user"]["user"] = count($users);
        $data["user"]["admin"] = count($admins);

        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/admin/index.php';
        include __DIR__ . '/../views/footer.php';
    }

}