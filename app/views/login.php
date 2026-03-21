<h1>Login</h1>
<?php if (!empty($error)): ?>
    <p class="error"><?= $this->helper->h($error) ?></p>
<?php endif; ?>
    <form method="post" action="/login">
        <input type="hidden" name="csrf" value="<?= $this->helper->h($this->helper->csrf_token()) ?>">

        <div class="form-group col-md-6 col-xs-12">
            <label for="name">Username</label>
            <input type="text" class="form-control" id="name" name="name" required placeholder="Your Name">
        </div>
        <div class="form-group col-md-6 col-xs-12">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required placeholder="Password">
        </div>
        <div class="col-md-12 col-xs-12">
            <button type="submit" class="btn btn-default">Sign in</button>
        </div>
    </form>
