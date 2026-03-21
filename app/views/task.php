<?php
/**
 * @var string $title
 * @var array $task
 * @var array $statusKeys
 * @var string $statusText
 */
?>
<div class="status-links">
    <a href="/tasks/<?= $statusText ?>" class="btn btn-xs btn-link'">Back to Tasks</a>
</div>
<h1><?= $title ?></h1>
<?php if (!$task): ?>
    <p class="notice">No task found.</p>
<?php else: ?>
    <article class="task">
        <div class="article-header">
            <div></div>
            <div>
                <?php if ($this->user["is_admin"]): ?>
                    <span class="status-badge user-badge">User: <?= $task['users_name'] ?></span>
                <?php endif; ?>
                <?php $_status = $task["status"] == 0 ? (
                $task["due_date"] < gmdate("Y-m-d") ? $statusKeys[3] : $statusKeys[0]) : $statusKeys[$task["status"]]; ?>
                <span class="status-badge <?= $this->taskStatus[$_status] ?>"><?= ucfirst($_status) ?></span>
            </div>
        </div>
        <?= $this->parsedown->text($task['body']) ?>
        <div class="article-footer">
            <small>
                Published <?= $this->helper->h(date('j M Y, H:i', strtotime($task['created_at']))) ?>
                <?php if ($task['created_at'] != $task['updated_at']): ?>
                    • Updated <?= $this->helper->h(date('j M Y, H:i', strtotime($task['updated_at']))) ?>
                <?php endif; ?>
            </small>
            <small>Due Date <?= $this->helper->h(date('j M Y', strtotime($task['due_date']))) ?></small>
        </div>
    </article>
<?php endif; ?>
