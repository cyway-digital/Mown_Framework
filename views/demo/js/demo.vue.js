const ChildComponent = {
    template: '#child-component',
    inject: ['doRequest','newAlert'],
    data() {
        return {
            controller: 'demo',
            loading: false,
            randomNumber: false,
            randomNumberFrom: 1,
            randomNumberTo: 99,
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
    },
}

