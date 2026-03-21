<?php
/**
 * @var string $title
 * @var array $data
 * @var array $statusKeys
 */
?>
<h1><?= $title ?></h1>
<div class="dashboard-links">
    <a href="/user-list" class="btn btn-link btn-sm">User Management</a>
    <a href="/task-list" class="btn btn-primary btn-sm">Task Management</a>
</div>

    <form method="post" action="/task-submit">
        <input type="hidden" name="csrf" value="<?= $this->helper->h($this->helper->csrf_token()) ?>">
        <input type="hidden" name="id" value="<?= $data["task"]["id"] ?>">

        <div class="form-group col-md-4 col-xs-12">
            <label for="user_id">User</label>
            <select class="form-control" id="user_id" name="user_id">
                <?php foreach ($data["user"] as $user): ?>
                <option value="<?= $user["id"] ?>" <?= $user["id"] == $data["task"]["user_id"] ? "selected" : "" ?>><?= $user["name"] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group col-md-4 col-xs-12">
            <label for="status">Status</label>
            <?php if($data["task"]["id"] == 0): ?>
                <input type="hidden" id="status" name="status" value="0">
                <input type="text" class="form-control" disabled value="Pending">
            <?php else: ?>
            <select class="form-control" id="status" name="status">
                <?php for ($s=0; $s<3; $s++): ?>
                <option value="<?= $s ?>" <?= $s == $data["task"]["status"] ? "selected" : "" ?>><?= $statusKeys[$s] ?></option>
                <?php endfor; ?>
            </select>
            <?php endif; ?>
        </div>
        <div class="form-group col-md-4 col-xs-12">
            <label for="due_date">Due Date</label>
            <div class="input-group">
                <input type="text" class="form-control" id="due_date" name="due_date" required value="<?= $data["task"]["due_date"] ?>">
            </div>
        </div>

        <div class="form-group col-md-12 col-xs-12">
            <label for="name">Task Title</label>
            <input type="text" class="form-control" id="name" name="name" required placeholder="Task Title" value="<?= $data["task"]["name"] ?>">
        </div>

        <div class="form-group col-md-12 col-xs-12">
            <label for="body">Task Description</label>
            <textarea class="form-control" id="body" name="body" placeholder="Task Description"><?= $data["task"]["body"] ?></textarea>
            <p class="help-block">You can use Markdown syntax to create the document.</p>
        </div>
        <div class="form-footer col-md-12 col-xs-12">
            <button type="submit" class="btn btn-default"><?= $data["submit"] ?></button>
            <a href="/task-list" class="btn btn-danger">Cancel</a>
        </div>
    </form>

<script>
    var picker = new Pikaday({
        field: document.getElementById('due_date'),
        format: 'D MMM YYYY',
        minDate: new Date(),
    });

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