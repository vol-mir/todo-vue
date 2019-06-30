import './bootstrap'

import Vue from 'vue'
import VueRouter from 'vue-router'

import HelloWorld from './components/HelloWorld'
import MainApp from './MainApp'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      name: 'hello',
      path: '/',
      component: HelloWorld
    }
  ]
})

window.onload = function () {
  window.Vue = new Vue({
    el: '#app',
    router,
    render: h => h(MainApp)
  })
}
