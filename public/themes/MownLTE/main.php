<!DOCTYPE html>
<html lang="en">

    <?php require 'components/header.php'; ?>
    <body class="hold-transition sidebar-mini accent-olive">

        <div class="wrapper" id="mainMownApp">
            <?php require 'components/navbar.php'; ?>
            <?php require 'components/sidebar.php'; ?>

            <div class="content-wrapper">
                <child-component ref="child" />
            </div>

            <?php require 'components/control_sidebar.php'; ?>
            <?php require 'components/footer.php'; ?>
        </div>

        <script type='vue-template' id="child-component">
        <?php require 'views/' . $page . '.php'; ?>
        </script>

        <script src="<?= URL.THEME_PATH;?>plugins/jquery/jquery.min.js"></script>
        <script src="<?= URL.THEME_PATH;?>plugins/popper/umd/popper.min.js"></script>
        <script src="<?= URL.THEME_PATH;?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?= URL.THEME_PATH;?>assets/js/adminlte.min.js"></script>
        <script src="<?= URL.THEME_PATH;?>assets/js/xeditable.min.js"></script>

        <script src="<?= URL;?>public/js/luxon.min.js"></script>
        <script src="<?= URL;?>public/js/mown.js"></script>

        <?php if (defined('SYS_STAGE') && SYS_STAGE !== 'PROD') { ?>
        <script src="<?= URL;?>public/js/vue/vue.js"></script>
        <?php } else { ?>
        <script src="<?= URL;?>public/js/vue/vue.min.js"></script>
        <?php } ?>

        <?php if (isset($this->js)) { foreach ($this->js as $path) { ?>
            <script src="<?php echo $path;?>"></script>
        <?php } } ?>

        <script src="<?= URL;?>public/js/mown.vue.js"></script>
        <script src="<?= URL;?>public/plugins/sweetalert/sweetalert.min.js"></script>


    </body>
</html>
