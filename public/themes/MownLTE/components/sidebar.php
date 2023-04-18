<aside class="main-sidebar sidebar-light-success elevation-4">
    <a href="<?php echo URL;?>" class="brand-link bg-light text-sm">
        <img src="<?php echo URL.THEME_PATH.'assets/imgs/';?>logo_nav.png" alt="<?php echo SYS_NAME;?> Logo" class="brand-image" style="opacity: .8">
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo URL.IMG_PATH;?>user.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <div v-show="env" class="d-block">{{env?.user?.firstname}} {{env?.user?.lastname}}</div>
            </div>
        </div>

        <!--
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>
        -->

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?=URL?>dashboard" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <!--
                <li class="nav-item">
                    <a href="<?=URL?>customers" class="nav-link">
                        <i class="nav-icon fa fa-user-group"></i>
                        <p>Customers</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=URL?>campaigns" class="nav-link">
                        <i class="nav-icon far fa-envelope"></i>
                        <p>Campaigns</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=URL?>settings" class="nav-link">
                        <i class="nav-icon fa-solid fa-gears"></i>
                        <p>Settings</p>
                    </a>
                </li>
                -->
            </ul>
        </nav>
    </div>
</aside>