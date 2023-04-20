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
        getRandomNumber() {
            this.loading = true

            let data = this.doRequest('GET', this.$parent.appUrl + this.controller + '/randomNumber/' + this.randomNumberFrom + '/' + this.randomNumberTo);
            data.then(a => {
                this.randomNumber = a.result
                this.loading = false

            }).catch(error => { })
        },
    },
}

