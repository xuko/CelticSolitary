<?php require __DIR__ . '/header.php' ?>

<div id="message-bar" class="swagger-ui-wrap message-success">
  <?php if(!empty($error)): ?>
  <p class="error"><?= $error ?></p>
  <?php endif; ?>  
</div>

<div id="swagger-ui-container" class="swagger-ui-wrap">
  <?php if (!isset($_SESSION['user'])): ?>
    <form action="<?= filter_input(INPUT_SERVER, 'PATH_INFO') ?>" method="POST">
      <p class="code">Username:
        <input class="parameter" type="text" name="username" id="username" 
          required="required" value="<?= $username_value ?>" tabindex="1" />
        <?php if ($username_error): ?>
          <span class="api-ic ic-error"></span>
          <span class="message-fail"><?= $username_error ?></span>
        <?php endif ?>
      </p>
      <p class="code">Password:
        <input class="parameter" type="password" name="password" id="password" tabindex="2" />
        <?php if ($password_error): ?>
          <span class="api-ic ic-error"></span>
          <span class="message-fail"><?= $password_error ?></span>
        <?php endif ?>
      </p>
      <p><input type="submit" value="Login" tabindex="3" />
    </form>
  <?php else: ?>
    <p><a class="link_url" href="/">Back Home</a></p>
    <p class="code">
         | <a class="link_url" href="logout">Logout</a>
         | Username: <?= $_SESSION['user'] ?>
         | Admin <span class="api-ic ic-<?= $_SESSION['isAdmin'] ? 'on' : 'off' ?>"></span>
    </p>
  <?php endif ?>
</div>

<?php require __DIR__ . '/footer.php' ?>