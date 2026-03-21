<?php
/**
 * @var string $title
 * @var array $data
 * @var array $statusKeys
 */
$taskStatus = array_keys($this->taskStatus);
?>
<h1><?= $title ?></h1>
<div class="list-head-container">
    <div>
        <a href="/task-add" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-plus"></i> Add Task</a>
    </div>
    <div class="dashboard-links">
        <a href="/user-list" class="btn btn-link btn-sm">User Management</a>
        <a href="/task-list" class="btn btn-primary btn-sm">Task Management</a>
    </div>
</div>


<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th class="col-md-5">Name</th>
        <th class="col-md-2">Status</th>
        <th class="col-md-2">Due Date</th>
        <th class="actions">#</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data["tasks"] as $task): ?>
    <?php $_status = $task["status"] == 0 ? (
        $task["due_date"] < gmdate("Y-m-d") ? $statusKeys[3] : $statusKeys[0]) : $statusKeys[$task["status"]]; ?>
    <tr>
        <td><?= $task["name"] ?></td>
        <td><span class="status-badge <?= $this->taskStatus[$_status] ?>"><?= ucfirst($_status) ?></span></td>
        <td><?= gmdate("j M Y", strtotime($task["due_date"])) ?></td>
        <td class="actions">
            <button type="button" class="btn btn-link btn-xs viewButton" data-target="/task-view/<?= (int)$task['id'] ?>">View</button>
            <a href="/task-edit/<?= (int)$task['id'] ?>" class="btn btn-link btn-xs">Edit</a>
            <button type="button" class="btn btn-danger btn-xs delButton" data-target="/task-delete/<?= (int)$task['id'] ?>">Delete</button>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>

</table>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const delButtons = document.querySelectorAll('.delButton');

        delButtons.forEach(
            (element) => element.addEventListener("click", function (e) {
                e.preventDefault();
                document.querySelector('#confirmForm').setAttribute("action",element.dataset.target);
                document.querySelector('#confirmModal').style.display = "flex";
            }, true)
        )
    })
</script>