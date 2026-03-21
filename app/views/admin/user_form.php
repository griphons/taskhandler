<?php
/**
* @var string $title
* @var array $data
*/
?>
<h1><?= $title ?></h1>
<div class="dashboard-links">
    <a href="/user-list" class="btn btn-primary btn-sm">User Management</a>
    <a href="/task-list" class="btn btn-link btn-sm">Task Management</a>
</div>

    <form method="post" action="/user-submit">
        <input type="hidden" name="csrf" value="<?= $this->helper->h($this->helper->csrf_token()) ?>">
        <input type="hidden" name="id" value="<?= $data["user"]["id"] ?>">

        <div class="form-group col-md-6 col-xs-12">
            <label for="name">Username</label>
            <input type="text" class="form-control" id="name" name="name" required placeholder="User Name" value="<?= $data["user"]["name"] ?>">
            <p class="help-block">It must be unique</p>
        </div>
        <div class="form-group col-md-6 col-xs-12">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" <?= $data["user"]["id"] == 0 ? "required" : "" ?> placeholder="Password">
            <?php if ($data["user"]["id"] !== 0): ?>
            <p class="help-block">Leave empty if you don't want to change it</p>
            <?php endif; ?>
        </div>
        <div class="checkbox col-md-12 col-xs-12">
            <label>
                <input type="checkbox" value="" <?= $data["user"]["is_admin"] == 1 ? "checked" : "" ?> <?= $data["user"]["id"] === 1 ? "disabled" : "" ?> name="is_admin">
                This is an <strong>admin</strong> user
            </label>
        </div>
        <div class="form-footer col-md-12 col-xs-12">
            <button type="submit" class="btn btn-default"><?= $data["submit"] ?></button>
            <a href="/user-list" class="btn btn-danger">Cancel</a>
        </div>
    </form>

<script>
    const errorElements = document.querySelectorAll(".error");
    errorElements.forEach(element => {
        element.classList.remove("error");
    })

    const errorCell = <?= json_encode($data["error"]) ?>;
    if (errorCell) {
        errorCell.forEach((cell) => {
            document.querySelector("#" + cell).classList.add("error");
            document.querySelector("label[for=" + cell + "]").classList.add("error");
        });
    }
</script>