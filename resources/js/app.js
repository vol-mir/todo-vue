import './bootstrap'

import Vue from 'vue'
import VueRouter from 'vue-router'

import TodoList from './components/TodoList'
import MainApp from './MainApp'

import { store } from './store'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      name: 'todo_list',
      path: '/',
      component: TodoList
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
