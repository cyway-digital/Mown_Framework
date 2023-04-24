<div class="row">
    <div class="col-md-10">
        <h3>Generate random password</h3>
        <form class="form-horizontal">
            <div class="form-group">
                <label for="" class="col-sm-9 control-label">Password</label>
                <div class="col-sm-9">
                    <input type="text" disabled :value="randomPassword?.pwd" style="width: 100%">
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-9 control-label">Hash</label>
                <div class="col-sm-9">
                    <input type="text" disabled :value="randomPassword?.hash" style="width: 100%">
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-9 control-label">Salt</label>
                <div class="col-sm-9">
                    <input type="text" disabled :value="randomPassword?.salt" style="width: 100%">
                </div>
            </div>
        </form>

        <a href="#!" v-show="!loading" @click="getRandomPassword()" class="btn btn-info">Fetch</a>

    </div>
</div>