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

    public function index() {
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

    public function user_list() {
        $title = 'User List';

        $data = [];

        $data["users"] = $this->crud->reads(["id","name","is_admin"])
            ->table('users')->get();

        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/admin/user_list.php';
        include __DIR__ . '/../views/footer.php';
    }

    public function user_add() {
        $title = 'Add User';

        $data = [];
        $data["user"] = [
            "id" => 0,
            "name" => "",
            "is_admin" => 0,
        ];
        $data["submit"] = "Create User";
        $data["error"] = "";

        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/admin/user_form.php';
        include __DIR__ . '/../views/footer.php';
    }

    public function user_edit($id) {
        $title = 'Edit User';

        $data = [];
        $data["user"] = $this->crud->read()
            ->table('users')
            ->whereId($id)->get();
        $data["submit"] = "Update User";
        $data["error"] = "";

        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/admin/user_form.php';
        include __DIR__ . '/../views/footer.php';
    }

    public function user_submit() {
        $newId = $_POST["id"];
        $newPassword = $_POST["password"];
        $newData = [
            "name" => $_POST["name"],
            "is_admin" => isset($_POST["is_admin"]) ? 1 : 0,
        ];

        $error = [];
        $errorCell = [];

        $unique = $this->crud->read()
            ->table('users')
            ->where('id','','!=', $newId)
            ->where('name','','=',$newData["name"])
            ->get();
        if($unique) {
            $error[] = "User name already exists";
            $errorCell[] = "name";
        }

        if(!empty($newPassword) && strlen($newPassword) < 6) {
            $error[] = "Password must be at least 6 characters";
            $errorCell[] = "password";
        }

        if($newId == 0) {
            $title = 'Add User';

            if(count($errorCell) == 0) {
                $newData["password"] = password_hash($newPassword, PASSWORD_DEFAULT);
                $newData["created_at"] = date("Y-m-d H:i:s");
                $this->crud->insert($newData)
                    ->table('users')->get();
                $cookieContent = "bg-success;The user has been created";
            } else {
                $newData["id"] = $newId;
                $data = [
                    "user" => $newData,
                    "submit" => "Create User",
                    "error" => $errorCell
                ];

                $cookieContent = "bg-danger;".implode("<br>",$error);
            }
        } else {
            $title = 'Edit User';

            if(count($errorCell) == 0) {
                if(!empty($newPassword)) {
                    $newData["password"] = password_hash($newPassword, PASSWORD_DEFAULT);
                }
                $this->crud->update($newData)
                    ->table('users')
                    ->whereId($newId)
                    ->get();
                $cookieContent = "bg-success;The user has been updated";
            } else {
                $newData["id"] = $newId;
                $data = [
                    "user" => $newData,
                    "submit" => "Update User",
                    "error" => $errorCell
                ];

                $cookieContent = "bg-danger;".implode("<br>",$error);
            }
        }

        setcookie("flashCookie", $cookieContent, time() + 3600,"/");
        if(count($errorCell) == 0) {
            $this->helper->redirect('/user-list');
        } else {
            include __DIR__ . '/../views/header.php';
            include __DIR__ . '/../views/admin/user_form.php';
            include __DIR__ . '/../views/footer.php';
        }
    }

    public function user_delete($id) {
        if($id == 1) {
            $cookieContent = "bg-danger;The main admin cannot be deleted!";
        } else {
            $del = $this->crud->delete()
                ->table('users')
                ->whereId($id)->get();
            $cookieContent = "bg-success;The user has been deleted!";
        }

        setcookie("flashCookie", $cookieContent, time() + 3600,"/");
        $this->helper->redirect('/user-list');
    }

}