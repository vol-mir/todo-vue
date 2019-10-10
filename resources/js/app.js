import './bootstrap'
import './assets/css/my-login.css'

import Vue from 'vue'
import VueNoty from 'vuejs-noty'
import VueLocalStorage from 'vue-localstorage'
import VTooltip from 'v-tooltip'

// LightBootstrap plugin
import LightBootstrap from './light-bootstrap-main'

import router from '@/routes.js'
import store from '@modules/index.js'

import App from '@/App.vue'

Vue.use(VueNoty, {
  layout: 'bottomLeft'
})

Vue.use(VueLocalStorage)
Vue.use(LightBootstrap)
Vue.use(VTooltip)

window.onload = function () {
  store.dispatch('init')
  window.Vue = new Vue({
    router,
    store,
    el: '#app',
    render: h => h(App)
  })
}
