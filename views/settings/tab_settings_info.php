<div class="row">
    <div class="col-md-10">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="inputSysMail" class="col-sm-9 control-label">Platform</label>
                <div class="col-sm-9">
                    <input type="text" disabled value="<?php echo PHP_OS . " - " . php_uname() ?>" style="width: 100%">
                </div>
            </div>
            <div class="form-group">
                <label for="inputSysMail" class="col-sm-9 control-label">PHP Version</label>
                <div class="col-sm-9">
                    <input type="text" disabled value="<?php echo phpversion(); ?>" style="width: 100%">
                </div>
            </div>
            <div class="form-group" @click="newAlert('gino','hey','danger')">
                <label for="inputSysMail" class="col-sm-9 control-label">PHP openssl_random_pseudo_bytes</label>
                <div class="col-sm-9">
                    <input type="text" disabled value="<?php echo is_callable("openssl_random_pseudo_bytes") ? 'OK' : 'NO'; ?>" style="width: 100%">
                </div>
            </div>
            <div class="form-group">
                <label for="inputSysMail" class="col-sm-9 control-label">MySQL Version</label>
                <div class="col-sm-9">
                    <input type="text" disabled value="<?php echo $this->mySQLVer; ?>" style="width: 100%">
                </div>
            </div>
        </form>

        <h4>PHP Extensions Loaded</h4>
        <pre>
            <?php print_r(get_loaded_extensions()); ?>
        </pre>

    </div>
</div>