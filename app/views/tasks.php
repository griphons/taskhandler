<?php
/**
 * @var string $title
 * @var array $tasks
 * @var array $statusKeys
 * @var string $statusText
 */
?>
<div class="status-links">
    <a href="/tasks" class="btn btn-xs <?= $statusText == 'all' ? 'btn-primary' : 'btn-link' ?>">All Task</a>
    <?php foreach ($statusKeys as $key): ?>
    <a href="/tasks/<?= $key ?>" class="btn btn-xs <?= $key == $statusText ? 'btn-primary' : 'btn-link' ?>"><?= ucfirst($key) ?></a>
    <?php endforeach; ?>
</div>
<h1><?= $title ?></h1>
<?php if (!$tasks): ?>
    <p class="notice">No tasks yet.</p>
<?php endif; ?>
<?php foreach ($tasks as $task): ?>
    <article class="task">
        <div class="article-header">
            <h2><a href="/task/<?= $this->helper->h($task['slug']) ?>"><?= $this->helper->h($task['name']) ?></a></h2>
            <div>
                <?php if ($this->user["is_admin"]): ?>
                    <span class="status-badge user-badge">User: <?= $task['users_name'] ?: "unknown" ?></span>
                <?php endif; ?>
                <?php

                    if ($task['status'] == 0 && $task['due_date'] < gmdate("Y-m-d")) {
                        $_status = 'missed';
                    } else {
                        $_status = $statusKeys[$task['status']];
                    }
                ?>
                <span class="status-badge <?= $this->taskStatus[$_status] ?>"><?= ucfirst($_status) ?></span>
            </div>
        </div>
        <?= $this->parsedown->text(substr($task['body'],0,100)."... [more](/task/". $this->helper->h($task['slug']) .")") ?>
        <div class="article-footer">
            <small>Published <?= $this->helper->h(date('j M Y, H:i', strtotime($task['created_at']))) ?></small>
            <small>Due Date <?= $this->helper->h(date('j M Y', strtotime($task['due_date']))) ?></small>
        </div>
    </article>
<?php endforeach; ?>
