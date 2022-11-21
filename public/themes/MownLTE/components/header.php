<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex" />
    <meta name="author" content="CyWay | M. Vulcano | info@cyway.it">
    <meta name="token" content="<?php echo Session::get('token') ?? '';?>">
    <title><?php echo SYS_NAME; echo $this->title ? " | ".$this->title : '';?></title>

    <?php include_once "favicon.php";?>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= URL.THEME_PATH;?>plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= URL.THEME_PATH;?>assets/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= URL.THEME_PATH;?>assets/css/mown.css">
    <link rel="stylesheet" href="<?= URL.THEME_PATH;?>assets/css/xeditable.css"/>
    <link rel="stylesheet" href="<?= URL;?>public/plugins/sweetalert/sweetalert.css"/>

    <?php if (isset($this->css)) { foreach ($this->css as $path) { ?>
    <link rel="stylesheet" href="<?php echo URL.$path; ?>">
    <?php }} ?>

    <?php include_once "extra_header_code.php"; // Useful for Facebook Pixel, Google Analytics, etc...?>

    <script>var appUrl = '<?php echo URL;?>';</script>

</head>