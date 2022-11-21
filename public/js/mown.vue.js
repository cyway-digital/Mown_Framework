const RootComponent = {
    data: function () {
        return {
            env: {},
            currentUri: currentUri,
            appUrl: appUrl,
            mownToken: mownToken,
        }
    },
    methods: {
        doRequest: function(type,url, body = null) {
            const requestOptions = {
                method: type,
                headers: { 'X-Mown-Token': this.mownToken },
            };

            if (body) {
                requestOptions.body = JSON.stringify(body)
            }

            return fetch(url,requestOptions)
                .then(async response => {
                    let responseJson = await response.json();

                    if (!response.ok) {
                        let errorMsg = '('+response.statusText+') - ' + responseJson.error_msg;
                        throw Error(errorMsg);
                    }

                    return responseJson;
                })
                .catch(error => {
                    alert('ERRORE: '+error)
                    throw Error(error)
                });
        },
    },
    provide() {
        return {
            doRequest: function(type,url, body = null) {
                const requestOptions = {
                    method: type,
                    headers: { 'X-Mown-Token': this.$parent.mownToken },
                };

                if (body) {
                    requestOptions.body = JSON.stringify(body)
                }

                return fetch(url,requestOptions)
                    .then(async response => {
                        let responseJson = await response.json();

                        if (!response.ok) {
                            let errorMsg = '('+response.statusText+') - ' + responseJson.error_msg;
                            throw Error(errorMsg);
                        }

                        return responseJson;
                    })
                    .catch(error => {
                        this.newAlert(error,'ERRORE','danger')
                        throw Error(error)
                    });
            },
            newAlert: function(msg,title,cls) {
                $(document).Toasts('create', {
                    class: 'bg-'+cls,
                    autohide: true,
                    delay: 4000,
                    title: title,
                    subtitle: '',
                    body: msg
                })
            },
            formatDate: function(date,format) {
                if (!date || !format) {
                    return false;
                }

                return luxon.DateTime.fromFormat(date,'yyyy-MM-dd HH:mm:ss').toFormat(format);
            },
            formatCurrency: function(n) {
                let eur = Intl.NumberFormat("it-IT", {
                    style: "currency",
                    currency: "EUR",
                })
    
                return eur.format(n)
            }
        }
    },
    mounted() {
        let env = this.doRequest( 'GET',this.appUrl+'service/getEnv');
        env.then(a => this.env = a).catch(error => {})
    }
}

const mownApp = Vue.createApp(RootComponent)

mownApp.component('child-component',ChildComponent);

const mownAppMount = mownApp.mount('#mainMownApp');