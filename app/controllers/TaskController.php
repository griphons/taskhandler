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

    public function index() {
        $statusKeys = array_keys($this->taskStatus);
        $title = 'Task List';

        $data = [];

        $data["tasks"] = $this->crud->reads(["id","name","status","due_date"])
            ->table('tasks')
            ->join('users','name',['user_id','id'])
            ->get();

        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/admin/task_list.php';
        include __DIR__ . '/../views/footer.php';

    }

    public function view($id) {

    }

    public function create() {

    }

    public function update($id) {

    }

    public function submit() {

    }

    public function delete($id) {

    }

}