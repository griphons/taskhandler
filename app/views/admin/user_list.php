<?php
/**
 * @var string $title
 * @var array $data
 */
?>
<h1><?= $title ?></h1>
<div class="list-head-container">
    <div>
        <a href="/user-add" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-plus"></i> Add User</a>
    </div>
    <div class="dashboard-links">
        <a href="/user-list" class="btn btn-primary btn-sm">User Management</a>
        <a href="/task-list" class="btn btn-link btn-sm">Task Management</a>
    </div>
</div>


<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th class="col-md-5">Name</th>
        <th class="col-md-5">Status</th>
        <th class="actions">#</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data["users"] as $user): ?>
    <tr>
        <td><?= $user["name"] ?></td>
        <td><?= $user["is_admin"] == 1 ? "Admin" : "Non-admin" ?></td>
        <td class="actions">
            <a href="/user-edit/<?= (int)$user['id'] ?>" class="btn btn-link btn-xs">Edit</a>
            <button type="button" class="btn btn-danger btn-xs delButton" data-target="/user-delete/<?= (int)$user['id'] ?>">Delete</button>
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