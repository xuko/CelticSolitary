<!DOCTYPE html>
<html>
<head>
   <title>[tdw] User Api: login</title>
   <base href="<?= dirname(filter_input(INPUT_SERVER, 'PATH_INFO')) . '/' ?>" >
   <link href='/api/css/typography.css' media='screen' rel='stylesheet' type='text/css'/>
   <link href='/api/css/reset.css' media='screen' rel='stylesheet' type='text/css'/>
   <link href='/api/css/screen.css' media='screen' rel='stylesheet' type='text/css'/>
</head>

<body class="swagger-section">
  <div id="header">
    <div class="swagger-ui-wrap">
      <a id="logo" href="/">[TDW] User REST API</a>

      <?php if (empty($_SESSION['user'])): ?>
        | <a class="link_url" href="login" id="login">Login</a>
      <?php else: ?>
        | <a class="link_url" href="logout" id="logout">Logout</a>
      <?php endif; ?>

    </div>
  </div>