<?php
/**
 * @var string $title
 * @var array $data
 */
?>
<h1><?= $title ?></h1>
<div class="dashboard-links">
    <a href="/user-list" class="btn btn-link btn-sm">User Management</a>
    <a href="/task-list" class="btn btn-link btn-sm">Task Management</a>
</div>
<div class="dashboard">

    <div class="dashboard-box">
        <div class="icon">
            <i class="glyphicon glyphicon-user"></i>
        </div>
        <div class="dashboard-title">
            Users
        </div>
        <div class="dashboard-item">
            <span class="status-badge user-badge">All Users: <?= $data["user"]["all"] ?></span>
        </div>
        <div class="dashboard-item">
            <span class="status-badge bg-info">Non-admins: <?= $data["user"]["user"] ?></span>
        </div>
        <div class="dashboard-item">
            <span class="status-badge bg-success">Admins: <?= $data["user"]["admin"] ?></span>
        </div>
    </div>

    <div class="dashboard-box">
        <div class="icon">
            <i class="glyphicon glyphicon-tasks"></i>
        </div>
        <div class="dashboard-title">
            Tasks
        </div>
        <?php foreach ($data["status"] as $key => $status): ?>
        <div class="dashboard-item">
            <span class="status-badge <?= $key == "all" ? "user-badge" : $this->taskStatus[$key] ?>"><?= ucfirst($key) ?> Tasks: <?= $status ?></span>
        </div>
        <?php endforeach; ?>
    </div>

</div>
