import './bootstrap'

import Vue from 'vue'
import VueRouter from 'vue-router'

import ToDoList from './components/ToDoList'
import MainApp from './MainApp'

import { store } from './store'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      name: 'to-do-list',
      path: '/',
      component: ToDoList
    }
  ]
})

window.onload = function () {
  window.Vue = new Vue({
    el: '#app',
    store,
    router,
    render: h => h(MainApp)
  })
}
