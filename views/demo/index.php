<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"> Example page</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a :href="this.$parent.appUrl">Home</a></li>
                    <li class="breadcrumb-item active">Demo</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Fetch Some internal data</h3>
                        </div>
                        <div class="card-body">
                            <div class="overlay-wrapper">
                                <div class="overlay" v-show="loading"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                    <div class="text-bold pt-2"></div>
                                </div>
                                <form class="form-horizontal">
                                    <div class="card-body">
                                        Get a random rumber between an interval
                                        <div class="form-group row">
                                            <div class="col-sm-10">
                                                <input v-model="randomNumberFrom" type="number" class="form-control" placeholder="From">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-10">
                                                <input v-model="randomNumberTo" type="number" class="form-control" placeholder="To">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <a href="#!" v-show="!loading" @click="getRandomNumber(false)" class="btn btn-info">Fetch</a>
                                        <a href="#!" v-show="!loading" @click="getRandomNumber(true)" class="btn btn-danger float-right">Fetch With Error</a>
                                        <div class="row" v-show="randomNumber">
                                            <div class="col-12">
                                                Your random number is {{randomNumber}}! Try again?
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Fetch Some External data <small>From randomuser.me public API</small></h3>
                        </div>
                        <div class="card-body box-profile">
                            <div class="overlay-wrapper">
                                <div class="overlay" v-show="loading"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                    <div class="text-bold pt-2"></div>
                                </div>
                                <div class="text-center" v-show="randomPerson">
                                    <img class="profile-user-img img-fluid img-circle" :src="{{randomPerson.picture.medium}}" alt="User profile picture">
                                </div>
                                <h3 class="profile-username text-center">{{randomPerson.name.title}} {{randomPerson.name.first}} {{randomPerson.name.last}}</h3>
                                <p class="text-muted text-center">{{randomPerson.location.city}} ({{randomPerson.location.state}} - {{randomPerson.location.country}})</p>
                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item">
                                        <b>E-mail</b> <a class="float-right">{{randomPerson.email}}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Phone</b> <a class="float-right">{{randomPerson.phone}}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Gender</b> <a class="float-right">{{randomPerson.gender}}</a>
                                    </li>
                                </ul>
                                <h3>Raw data:</h3>
                                <pre v-show="randomPerson">{{randomPerson}}</pre>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="#!" v-show="!loading" @click="getRandomPerson()" class="btn btn-success float-right">Fetch</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>