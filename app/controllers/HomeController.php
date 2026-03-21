<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    function __construct()
    {
        parent::__construct();

        if(!$this->helper->is_logged_in()){
            $this->helper->redirect('/login');
        }
    }

    public function index($statusText = 'all') {
        $statusKeys = array_keys($this->taskStatus);
        $status = array_search($statusText, $statusKeys);
        if($status === false) {$statusText = 'all';}

        $title = ucfirst($statusText)." Tasks";

        $tasks = $this->crud
            ->reads()->table('tasks')
            ->join('users', 'name',['user_id','id'])
        ;
        if($statusText !== 'all') {
            if($statusText == 'pending') {
                $tasks = $tasks->where('status','','=', 0);
                $tasks = $tasks->where('due_date','','>=', gmdate('Y-m-d'));
            } else if($statusText == 'missed') {
                $tasks = $tasks->where('status','','=', 0);
                $tasks = $tasks->where('due_date','','<', gmdate('Y-m-d'));
            } else {
                $tasks = $tasks->where('status','','=', $status);
            }
        }
        if ($this->user["is_admin"] != 1) {
            $tasks = $tasks->where('user_id','','=', $this->user["id"]);
        }
        $tasks = $tasks->orderby(['due_date','desc'])->get();

        if(!$tasks) {
            $title = "No Tasks Found";
        }

        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/tasks.php';
        include __DIR__ . '/../views/footer.php';
    }

    public function task(string $slug) {
        $task = $this->crud->read()
            ->table('tasks')
            ->join('users', 'name',['user_id','id'])
            ->where('slug', '','=',$slug);
        if ($this->user["is_admin"] != 1) {
            $task = $task->where('user_id', '','=',$this->user["id"]);
        }
        $task = $task->get();
        $title = "No Task Found";
        if($task) {
            $statusKeys = array_keys($this->taskStatus);
            $statusText = $statusKeys[$task["status"]];

            $title = $task["name"];
        }
        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/task.php';
        include __DIR__ . '/../views/footer.php';
    }

    public function error404() {
        http_response_code(404);
        $title = 'Not Found';
        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/404.php';
        include __DIR__ . '/../views/footer.php';

    }
}