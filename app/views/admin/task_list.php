<?php
/**
 * @var string $title
 * @var array $data
 * @var array $statusKeys
 * @var string $show
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
        <th class="col-md-3">Name</th>
        <th class="col-md-2">Status</th>
        <th class="col-md-2">user</th>
        <th class="col-md-2">Due Date</th>
        <th class="actions">#</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data["tasks"] as $task): ?>
    <?php $_status = $task["status"] == 0 ? (
        $task["due_date"] < gmdate("Y-m-d") ? $statusKeys[3] : $statusKeys[0]) : $statusKeys[$task["status"]]; ?>
    <tr id="row-<?= $task["id"] ?>">
        <td><?= $task["name"] ?></td>
        <td><span class="status-badge <?= $this->taskStatus[$_status] ?>"><?= ucfirst($_status) ?></span></td>
        <td><?= $task["users_name"] ?: "unknown" ?></td>
        <td><?= gmdate("j M Y", strtotime($task["due_date"])) ?></td>
        <td class="actions">
            <a class="btn btn-link btn-xs viewButton" href="/task-list/<?= (int)$task['id'] ?>">View</a>
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

        const contentModal = document.querySelector('#contentModal');
        const contentTitle = document.querySelector('#contentModal .content-box .content-header .content-title')
        const contentBadges = document.querySelector('#contentModal .content-box .content-header .content-badges')
        const contentBody = document.querySelector('#contentModal .content-box .content-body')
        const show = <?= $show ?>;

        if(JSON.stringify(show) !== '{}') {
            const taskRow = document.querySelector('#row-' + show.id);
            const username = '<span class="status-badge user-badge">User: ' + taskRow.querySelector(':scope td:nth-child(3)').innerHTML + '</span>';
            const status = taskRow.querySelector(':scope td:nth-child(2)').innerHTML;

            contentTitle.innerHTML = show.name;
            contentBadges.innerHTML = username + status;
            contentBody.innerHTML = show.body + '<div class="content-body-dates"><div>' + show.creation_date + '</div><div>' + show.finish_date + '</div></div>';
            contentModal.style.display = "flex";
        }

    })
</script>