</main>
<footer class="site-footer">
  <p>Made by Laszlo Kiss @2026</p>
</footer>

<div class="confirm-container" id="confirmModal">
    <div class="confirm-box">
        <div class="confirm-header">
            Confirmation Request
        </div>
        <div class="confirm-body">
            Are you sure you want to delete this user?
        </div>
        <div class="confirm-footer">
            <form method="post" id="confirmForm" action="">
                <input type="hidden" name="csrf" value="<?= $this->helper->h($this->helper->csrf_token()) ?>">
                <button class="btn btn-default" type="submit">Delete</button>
            </form>
            <button class="btn btn-danger" id="closeButton">Cancel</button>
        </div>
    </div>
</div>

<div id="flashModal">
    <div id="flashBox">
        <div id="flashContent"></div>
    </div>
</div>
</body>
</html>