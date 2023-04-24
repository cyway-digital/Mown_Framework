const ChildComponent = {
    template: '#child-component',
    inject: ['doRequest', 'newAlert', 'formatDate','formatCurrency'],
    data() {
        return {
            controller: 'demo',
            loading: false,
            randomNumber: false,
            randomNumberFrom: 1,
            randomNumberTo: 99,
            randomPerson: false,
            lastRotationDay: false
        }
    },
    methods: {
        getRandomNumber(err) {
            this.loading = true
            this.randomNumber = false

            let url = this.$parent.appUrl + this.controller + '/randomNumber/' + this.randomNumberFrom + '/' + this.randomNumberTo;

            if (err) {
                url = this.$parent.appUrl + this.controller + '/randomNumber/sample/' + this.randomNumberTo;
            }

            let data = this.doRequest('GET', url);
            data.then(a => {
                console.log(a)
                this.randomNumber = a.result
                this.loading = false
            }).catch(error => {
                this.loading = false
            })
        },
        getLastRotationDay() {
            this.loading = true
            this.lastRotationDay = false

            let url = this.$parent.appUrl + this.controller + '/getLogRotate';

            let data = this.doRequest('GET', url);
            data.then(a => {
                console.log(a)
                this.lastRotationDay = a
                this.loading = false
            }).catch(error => {
                this.loading = false
            })
        },
        getRandomPerson() {
            this.loading = true
            this.randomPerson = false

            let data = this.doRequest('GET', 'https://randomuser.me/api/');
            data.then(a => {
                console.log(a)
                this.randomPerson = a.results[0]
                this.loading = false
            }).catch(error => {
                this.loading = false
            })
        },
    },
}

