<?php

namespace App\Controllers;

use App\Controllers\CRUD;
use App\Controllers\HelperClass;
use Parsedown;

class BaseController
{

    protected CRUD $crud;
    public mixed $user;
    public HelperClass $helper;
    public Parsedown $parsedown;

    public array $taskStatus = [
        'pending' => 'bg-info',
        'completed' => 'bg-success',
        'failed' => 'bg-danger',
        'missed' => 'bg-warning',
    ];
    public function __construct()
    {
        $this->crud = new CRUD();
        $this->helper = new HelperClass();
        $this->parsedown = new Parsedown();
        $this->parsedown->setSafeMode(true);

        $this->user = $this->helper->is_logged_in() ?
            $this->crud->read()->table('users')->whereID($_SESSION['user_id'])->get()
            : null;
    }
}