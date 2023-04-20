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
                            <h3 class="card-title">Fetch Some external data</h3>
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
                                        <a href="#!" v-show="!loading" @click="getRandomNumber(false)" class="btn btn-danger float-right">Fetch With Error</a>
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
            </div>
        </div>
    </div>