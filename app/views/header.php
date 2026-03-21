<?php
/**
 * @var string $title
 * @var $this
 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $title ? "$title - " . $_ENV["APP_TITLE"] : $_ENV["APP_TITLE"] ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="stylesheet" href="/css/reset.css">
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
    <link rel="stylesheet" href="/css/styles.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
    <script src="/js/custom.js"></script>
</head>
<body>
<header class="site-header">
    <nav class="nav">
        <a href="/" class="brand">TaskHandler</a>
        <div class="spacer"></div>
        <?php if ($this->helper->is_logged_in()): ?>
            <?php if ($this->user["is_admin"]): ?>
            <a href="/" class="btn btn-link">Tasks</a>
            <a href="/admin" class="btn btn-link">Admin</a>
            <?php endif; ?>
            <form action="/logout" method="post" style="display:inline">
                <input type="hidden" name="csrf" value="<?= $this->helper->h($this->helper->csrf_token()) ?>">
                <button type="submit" class="btn btn-link">Logout</button>
            </form>
        <?php else: ?>
            <a href="/login">Login</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container">