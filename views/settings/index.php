<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a :href="this.$parent.appUrl">Home</a></li>
                    <li class="breadcrumb-item active">Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-olive card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-personal" data-toggle="pill" href="#custom-tabs-personal-tab" role="tab" aria-controls="custom-tabs-personal-tab" aria-selected="true">Personal Informations</a>
                    </li>
                    <?php if (Session::get('role') == "ADMIN") { ?>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-users" data-toggle="pill" href="#custom-tabs-users-tab" role="tab" aria-controls="custom-tabs-users-tab" aria-selected="true">Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-system-info" data-toggle="pill" href="#custom-tabs-system-info-tab" role="tab" aria-controls="custom-tabs-system-info-tab" aria-selected="false">System Informations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-utils" data-toggle="pill" href="#custom-tabs-utils-tab" role="tab" aria-controls="custom-tabs-system-info-tab" aria-selected="false">Utils</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-personal-tab" role="tabpanel" aria-labelledby="custom-tabs-personal-tab">
                        <?php require_once 'tab_password.php'; ?>
                    </div>
                    <?php if (Session::get('role') == "ADMIN") { ?>
                        <div class="tab-pane fade" id="custom-tabs-users-tab" role="tabpanel" aria-labelledby="custom-tabs-users-tab">
                            <?php require_once 'tab_users.php'; ?>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-system-info-tab" role="tabpanel" aria-labelledby="custom-tabs-system-info-tab">
                            <?php require_once 'tab_settings_info.php'; ?>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-utils-tab" role="tabpanel" aria-labelledby="custom-tabs-utils-tab">
                            <?php require_once 'tab_settings_utils.php'; ?>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
</div>