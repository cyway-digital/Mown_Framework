<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex" />
  <meta name="author" content="cyway.it">
  <title><?php echo SYS_NAME; ?> | Log in</title>

  <?php include_once APP_PATH . THEME_PATH . "components/favicon.php"; ?>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?php echo URL; ?>public/themes/<?php echo SYS_THEME; ?>/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?php echo URL; ?>public/themes/<?php echo SYS_THEME; ?>/assets/css/adminlte.min.css">
  <link rel="stylesheet" href="<?php echo URL; ?>public/css/animate.css">

</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <img src="<?php echo URL; ?>public/themes/<?php echo SYS_THEME; ?>/assets/imgs/logo.png" width="100px" class="animated flipInX">
    </div>
    <div class="card">
      <div class="card-body login-card-body">
        <div id="alert" class="alert alert-danger animated flipInX" style="display: none;"></div>
        <form class="form-login">
          <div class="input-group mb-3 animated flipInX">
            <input type="text" name="username" class="form-control" placeholder="username">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3 animated flipInX">
            <input type="password" name="password" class="form-control" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" id="submitLogin" class="btn btn-sm bg-gradient-success animated flipInX">Sign In</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="<?php echo URL; ?>public/themes/<?php echo SYS_THEME; ?>/plugins/jquery/jquery.min.js"></script>
  <script src="<?php echo URL; ?>public/themes/<?php echo SYS_THEME; ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo URL; ?>public/themes/<?php echo SYS_THEME; ?>/assets/js/adminlte.min.js"></script>
  <script src="<?php echo URL; ?>views/login/js/login.js"></script>
  <script src="<?php echo URL; ?>public/js/sha512.js"></script>

</body>

</html>