<h3>Change your Password</h3>

<div class="form-group row">
    <label for="inputEmail3" class="col-sm-2 col-form-label">Old Password</label>
    <div class="col-sm-10">
        <input type="password" class="form-control" v-model="oldPwd" placeholder="Old Password">
    </div>
</div>
<div class="form-group row">
    <label for="inputPassword3" class="col-sm-2 col-form-label">New Password</label>
    <div class="col-sm-10">
        <input type="password" class="form-control" v-model="pwd1" placeholder="New Password">
    </div>
</div>
<div class="form-group row">
    <label for="inputPwd2" class="col-sm-2 col-form-label">Repeat New Password</label>
    <div class="col-sm-10">
        <input type="password" class="form-control" v-model="pwd2" placeholder="Repeat Password">
    </div>
</div>
<button class="btn btn-success btn-sm" @click="newPwdSubmit">Submit</button>