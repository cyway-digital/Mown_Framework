<div class="box">
    
    <div class="box-header with-border">
        <h3 class="box-title">System E-Mail</h3>
    </div>
    
    <div class="box-body">
        <div class="row">
            <div class="col-md-10">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="inputSysMail" class="col-sm-3 control-label">E-Mail sistema</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="inputSysMail" value="<?php echo $this->sysMail; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button class="btn btn-primary" id="newSysMailSubmit">Invia</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="box-header with-border">
        <h3 class="box-title">Abilitazione Invio E-Mail <small>per tutto il sistema</small></h3>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-md-10">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="inputSysMail" class="col-sm-3 control-label">Invio Mail attivo</label>
                        <div class="col-sm-9">

                            <input type="checkbox" id="toggleSysMailCheckbox">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button class="btn btn-primary" id="toggleSysMailSubmit">Invia</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>