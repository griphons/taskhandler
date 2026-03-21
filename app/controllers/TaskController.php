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

    public function index($id = null) {
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

    public function create() {

    }

    public function update($id) {

    }

    public function submit() {

    }

    public function delete($id) {

    }

}