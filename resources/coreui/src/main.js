// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import App from './App'
import router from './router'

Vue.use(BootstrapVue)

import Axios from 'axios'
Vue.prototype.$http = Axios;

import Vuex from 'vuex';
Vue.use(Vuex);
import createPersistedState from 'vuex-persistedstate'
import * as Cookies from 'js-cookie'

const store = new Vuex.Store({
    state: {
        user: null // default value
    },
    getters: {
        getUser() {
            return state.user
        }
    },
    mutations: {
        changeUser(state, user) {
            console.log('Mutation: changeuser to', user)
            state.user = user
        }
    },
    plugins: [
        createPersistedState({
            storage: {
                getItem: key => Cookies.get(key),
                setItem: (key, state) => Cookies.set(key, state, { expires: 3, secure: true }),
                removeItem: key => Cookies.remove(key)
            }
          })
    ]
})

/* eslint-disable no-new */
new Vue({
    el: '#app',
    router,
    store,
    template: '<App/>',
    components: {
        App
    }
})
