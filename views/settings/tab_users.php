        <!-- Modals -->
        <div class="modal fade" id="newUserModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add new system user</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <div class="col-12">
                                    <input type="text" class="form-control" v-model="newUser.firstName" placeholder="First Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12">
                                    <input type="text" class="form-control" v-model="newUser.lastName" placeholder="Last Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12">
                                    <input type="email" class="form-control" v-model="newUser.email" placeholder="E-mail">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12">
                                    <input type="email" class="form-control" v-model="newUser.userName" @focus="buildUsername()" placeholder="Username">
                                </div>
                            </div>
                            <small class="text-muted">When you press "Save changes" button, a new standard user will be added to the system, and an e-mail with credentials will be sent to the address provided.</small>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="addNewUserRun()">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editUserModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit System User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-12 control-label">First Name</label>
                                <div class="col-12">
                                    <input type="text" class="form-control" v-model="editUserItem.name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-12 control-label">Last Name</label>
                                <div class="col-12">
                                    <input type="text" class="form-control" v-model="editUserItem.surname">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-12 control-label">Username</label>
                                <div class="col-12">
                                    <input type="text" class="form-control" v-model="editUserItem.username">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-12 control-label">Email</label>
                                <div class="col-12">
                                    <input type="email" class="form-control" v-model="editUserItem.email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-12 control-label">Role</label>
                                <div class="col-12">
                                    <select class="form-control" v-model="editUserItem.role">
                                        <option value="ADMIN">Admin</option>
                                        <option value="OPERATOR">Operator</option>
                                    </select>
                                </div>
                            </div>
                            <div v-if="editUserItem.id != this.$parent.env?.user?.uid">
                                <span v-show="editUserItem.role != 'ADMIN'">
                                    <div v-show="editUserItem.active == 1" class="btn btn-gradient btn-danger btn-sm btn-flat" @click="toggleUserActivation(editUserItem.id)">Block</div>
                                    <div v-show="editUserItem.active == 0" class="btn btn-gradient btn-success btn-sm btn-flat" @click="toggleUserActivation(editUserItem.id)">Activate</div>
                                </span>
                                &nbsp;<div v-show="editUserItem.active == 1" :class="{'disabled': operationInProgress == true }" class="btn btn-gradient btn-info btn-sm btn-flat" @click="resetUserPassword(editUserItem.id)">Reset Password</div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary pull-right" :class="{'disabled': operationInProgress == true }" @click="editUserRun()">Send</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="blockModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Sei sicuro di bloccare <b><span id="blockUserDetail"></span></b>?</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-warning"><b>Questo utente non sarà più in grado di accedere al sistema, ma tutte le sue informazioni saranno mantenute.</b></p>
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default pull-left" type="button">No, chiudi</button>
                        <button class="btn btn-warning toggleActivationButton" type="button" data-link="" data-action="block">Sì, blocca l'utente</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="activateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Sei sicuro di voler riattivare <b><span id="activateUserDetail"></span></b>?</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-success"><b>Questo utente sarà di nuovo in grado di accedere al sistema.</b></p>
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default pull-left" type="button">No, chiudi</button>
                        <button class="btn btn-success toggleActivationButton" type="button" data-link="" data-action="activate">Sì, attiva l'utente.</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Modals -->

        <h3><i class="fa fa-users"></i> USERS</h3>
        <div class="row">
            <div class="col-12 text-right">
                <button type="button" class="btn btn-warning btn-xs btn-flat" data-toggle="modal" data-target="#newUserModal">
                    <i class="fa fa-plus"></i> Create
                </button>

            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th @click="toggleUserSort('id')">
                                ID
                                <span v-show="usersSort=='id'"><i class="fa fa-sort-asc" v-show="usersSortDirection==0"></i> <i class="fa fa-sort-desc" v-show="usersSortDirection==1"></i></span>
                            </th>
                            <th @click="toggleUserSort('name')">
                                First Name
                                <span v-show="usersSort=='name'"><i class="fa fa-sort-asc" v-show="usersSortDirection==0"></i> <i class="fa fa-sort-desc" v-show="usersSortDirection==1"></i></span>
                            </th>
                            <th @click="toggleUserSort('surname')">
                                Last Name
                                <span v-show="usersSort=='surname'"><i class="fa fa-sort-asc" v-show="usersSortDirection==0"></i> <i class="fa fa-sort-desc" v-show="usersSortDirection==1"></i></span>
                            </th>
                            <th @click="toggleUserSort('email')">
                                E-Mail
                                <span v-show="usersSort=='email'"><i class="fa fa-sort-asc" v-show="usersSortDirection==0"></i> <i class="fa fa-sort-desc" v-show="usersSortDirection==1"></i></span>
                            </th>
                            <th @click="toggleUserSort('role')">
                                Role
                                <span v-show="usersSort=='role'"><i class="fa fa-sort-asc" v-show="usersSortDirection==0"></i> <i class="fa fa-sort-desc" v-show="usersSortDirection==1"></i></span>
                            </th>
                            <th @click="toggleUserSort('active')">
                                Status
                                <span v-show="usersSort=='active'"><i class="fa fa-sort-asc" v-show="usersSortDirection==0"></i> <i class="fa fa-sort-desc" v-show="usersSortDirection==1"></i></span>
                            </th>
                            <th @click="toggleUserSort('date_add')">
                                Creation Date
                                <span v-show="usersSort=='date_add'"><i class="fa fa-sort-asc" v-show="usersSortDirection==0"></i> <i class="fa fa-sort-desc" v-show="usersSortDirection==1"></i></span>
                            </th>
                            <th @click="toggleUserSort('last_login')">
                                Last Login
                                <span v-show="usersSort=='last_login'"><i class="fa fa-sort-asc" v-show="usersSortDirection==0"></i> <i class="fa fa-sort-desc" v-show="usersSortDirection==1"></i></span>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="u,k in users.list" :class="{'bg-blocked': u.active != 1, 'bg-admin': u.role == 'ADMIN' }">
                            <td>{{u.id}}</td>
                            <td>{{u.name}}</td>
                            <td>{{u.surname}}</td>
                            <td>{{u.email}}</td>
                            <td>{{u.role}}</td>
                            <td>
                                <span class="badge bg-success" v-show="u.active == 1">Active</span>
                                <span class="badge bg-danger" v-show="u.active == 0">Inactive</span>
                            </td>
                            <td>{{u.date_add}}</td>
                            <td>{{u.last_login}}</td>
                            <td>
                                <div class="btn btn-xs btn-flat btn-info" @click="editUser(k)">Edit</div>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>