<?php

namespace App\Controllers;

class TaskController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        if(!$this->helper->is_logged_in() || !$this->user['is_admin']){
            $this->helper->redirect('/');
        }
    }

    /**
     * Admin Task List
     * @param $id
     * @return void
     */
    public function index($id = null): void
    {
        $statusKeys = array_keys($this->taskStatus);
        $title = 'Task List';

        $show = "{}";
        if ($id) {
            $show = $this->crud->read()
                ->table('tasks')
                ->join('users','name',['user_id','id'])
                ->whereId($id)->get();

            $show['body'] = $this->parsedown->text($show['body']);
            $show["creation_date"] = "Published " . gmdate("j M Y H:i", strtotime($show["created_at"]));

            if ($show["created_at"] !== $show["updated_at"]) {
                $show["creation_date"] .= " • Updated " . gmdate("j M Y H:i", strtotime($show["updated_at"]));
            }

            $show["finish_date"] = "Due Date " . gmdate("j M Y", strtotime($show["due_date"]));
            $show = json_encode($show);
        }

        $data = [];

        $data["tasks"] = $this->crud->reads(["id","name","status","due_date"])
            ->table('tasks')
            ->join('users','name',['user_id','id'])
            ->get();

        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/admin/task_list.php';
        include __DIR__ . '/../views/footer.php';

    }

    /**
     * Admin Task Create to Form
     * @return void
     */
    public function create(): void
    {
        $title = 'Add Task';

        $statusKeys = array_keys($this->taskStatus);

        $data = [];
        $data["task"] = [
            "id" => 0,
            "name" => "",
            "body" => "",
            "status" => 0,
            "user_id" => 0,
            "due_date" => gmdate("j M Y", time() + 60 * 60 * 24 * 3),
        ];

        $data["user"] = $this->crud->reads(["id","name"])
            ->table('users')
            ->where("is_admin",'','!=',1)
            ->get();

        $data["submit"] = "Create New Task";
        $data["error"] = "";

        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/admin/task_form.php';
        include __DIR__ . '/../views/footer.php';
    }

    /**
     * Admin Task Update to Form
     * @param $id
     * @return void
     */
    public function update($id): void
    {
        $title = 'Edit Task';

        $statusKeys = array_keys($this->taskStatus);

        $data = [];
        $data["task"] = $this->crud->read()
            ->table('tasks')
            ->whereId($id)->get();
        $data["task"]["due_date"] = gmdate("j M Y", strtotime($data["task"]["due_date"]));

        $data["user"] = $this->crud->reads(["id","name"])
            ->table('users')
            ->where("is_admin",'','!=',1)
            ->get();

        $data["submit"] = "Update Task";
        $data["error"] = "";

        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/admin/task_form.php';
        include __DIR__ . '/../views/footer.php';
    }

    /**
     * Admin Task Submit and Process Data from Form
     * @return void
     */
    public function submit(): void
    {
        $newId = $_POST["id"];
        $data = [
            "user_id" => $_POST["user_id"],
            "name" => $this->helper->h($_POST["name"]),
            "status" => $_POST["status"],
            "due_date" => gmdate("Y-m-d", strtotime($_POST["due_date"])),
            "body" => $this->helper->h($_POST["body"]),
        ];

        if ($newId == 0) {
            $data['created_at'] = date("Y-m-d H:i:s");
            $data['updated_at'] = date("Y-m-d H:i:s");
            $data['slug'] = $this->helper->slugify($_POST["name"]);
            $this->crud->insert($data)
                ->table('tasks')->get();
            $cookieContent = "bg-success;The task has been created";
        } else {
            $data['updated_at'] = date("Y-m-d H:i:s");
            $data['slug'] = $this->helper->slugify($_POST["name"]);
            $upd = $this->crud->update($data)
                ->table('tasks')
                ->whereId($newId)
                ->get();
            $cookieContent = "bg-success;The task has been updated";
        }

        setcookie("flashCookie", $cookieContent, time() + 3600,"/");
        $this->helper->redirect('/task-list');
    }

    /**
     * Admin Task Delete
     * @param $id
     * @return void
     */
    public function delete($id): void
    {
        $this->crud->delete()
            ->table('tasks')
            ->whereId($id)->get();
        $cookieContent = "bg-success;The task has been deleted!";

        setcookie("flashCookie", $cookieContent, time() + 3600,"/");
        $this->helper->redirect('/task-list');
    }

}