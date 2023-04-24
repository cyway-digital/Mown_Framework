const ChildComponent = {
    template: '#child-component',
    inject: ['doRequest', 'newAlert'],
    data: function () {
        return {
            controller: 'settings',
            operationInProgress: false,
            usersSort: 'id',
            usersSortDirection: 1,
            users: {},
            newUser: {},
            editUserItem: {},
            loading: false,
            oldPwd: null,
            pwd1: null,
            pwd2: null,
            randomPassword: false
        }
    },
    mounted() {
        let proxy = this
        $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href") // activated tab
            if (target == '#custom-tabs-users-tab') {
                proxy.getUsers()
            }
        });

        $("#editUserModal").on("hidden.bs.modal", function () {
            proxy.getUsers()
            proxy.editUserItem = {}
        });

    },
    computed: {},
    methods: {
        newPwdSubmit() {

            if (!this.pwd1 || !this.pwd2 || !this.oldPwd) {
                this.newAlert("all fields are requested", 'ERROR', 'danger')
                return
            }

            if (this.pwd1 !== this.pwd2) {
                this.newAlert("new Passwords doesn't match", 'ERROR', 'danger')
                return
            }

            if (this.pwd2.length < 9) {
                this.newAlert("new Password must be longer than 9 chars", 'ERROR', 'danger')
                return
            }

            this.loading = true;

            data = this.doRequest('POST', this.$parent.appUrl + this.controller + '/changePwd', {
                oldPwd: hex_sha512(this.oldPwd),
                newPwd1: hex_sha512(this.pwd1),
                newPwd2: hex_sha512(this.pwd2),
            });
            data.then(a => {
                this.newAlert("Ok!", 'SUCCESS', 'success')
                this.loading = false
            }).catch(error => { })

        },
        getUsers() {
            // fetch users
            const prd = this.doRequest('POST', this.$parent.appUrl + 'settings/getUsers', {
                'sort': this.usersSort,
                'sort_dir': this.usersSortDirection,
            });
            prd.then(a => this.users = a).catch(error => { })
        },
        toggleUserSort(field) {
            if (this.usersSort == field) {
                this.usersSortDirection = !this.usersSortDirection
            } else {
                this.usersSort = field
            }

            this.getUsers()
        },
        buildUsername() {
            if (this.newUser.firstName?.length > 1 && this.newUser.lastName?.length > 1) {
                this.newUser.userName = Array.from(this.newUser.firstName)[0].toLowerCase() + this.newUser.lastName.toLowerCase().replace(/[^a-zA-Z]+/g, "");
            }
        },
        addNewUserRun() {
            if (this.operationInProgress) {
                return false;
            }

            this.operationInProgress = true;

            const c = this.doRequest('POST', this.$parent.appUrl + this.controller + '/addUser', this.newUser);
            c.then(a => {
                this.newAlert('Ok', 'User Added', 'success')

                $('#newUserModal').modal('hide');
                this.newUser = {}
                this.getUsers(function () { this.operationInProgress = false; })
            }).catch(error => { this.operationInProgress = false; })
        },
        editUser(k) {
            this.editUserItem = this.users.list[k]
            $('#editUserModal').modal('show');
        },
        editUserRun() {
            if (this.operationInProgress) {
                return false;
            }

            const c = this.doRequest('POST', this.$parent.appUrl + this.controller + '/editUser', this.editUserItem);
            c.then(a => {
                this.newAlert('Ok', 'User Edited', 'success')

                $('#editUserModal').modal('hide');
                this.editUserItem = {}
                this.getUsers(function () { this.operationInProgress = false; })
            }).catch(error => { this.operationInProgress = false; })
        },
        toggleUserActivation(uid) {
            if (this.operationInProgress) {
                return false;
            }

            const c = this.doRequest('POST', this.$parent.appUrl + this.controller + '/toggleUserActivation', {uid:uid});
            c.then(a => {
                this.newAlert('Ok', 'User Edited', 'success')

                $('#editUserModal').modal('hide');
                this.editUserItem = {}
                this.getUsers(function () { this.operationInProgress = false; })
            }).catch(error => { this.operationInProgress = false; })
        },
        resetUserPassword(uid) {
            if (this.operationInProgress) {
                return false;
            }

            let proxy = this

            swal({
                title: "Sure?",
                text: "Are you sure to reset this password? An e-mail will be sent to the user indicating the new credentials.",
                type: "warning",
                html: true,
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "No",
                confirmButtonText: "I'm sure",
                closeOnConfirm: false
            },
                function () {

                    proxy.operationInProgress = true;

                    const c = proxy.doRequest('POST', proxy.$parent.appUrl + proxy.controller + '/pwdReset', { uid: uid });
                    c.then(a => {
                        proxy.newAlert('Ok', 'User Edited', 'success')
                        $('#editUserModal').modal('hide');
                        swal.close();
                        proxy.editUserItem = {}
                        proxy.getUsers(function () { proxy.operationInProgress = false; })
                    }).catch(error => { proxy.operationInProgress = false; })
                });




        },
        getRandomPassword() {
            // fetch users
            const prd = this.doRequest('GET', this.$parent.appUrl + this.controller + '/getRandomPassword');
            prd.then(a => this.randomPassword = a).catch(error => { })
        },
    }
}
