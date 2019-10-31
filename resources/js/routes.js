import Vue from 'vue'
import VueRouter from 'vue-router'
import NProgress from 'nprogress'

import DashboardLayout from '@/layout/DashboardLayout.vue'

import MainPage from '@pages/MainPage.vue'
import NotFound from '@pages/NotFoundPage.vue'
import UserProfile from '@pages/UserProfile.vue'
import AboutPage from '@pages/AboutPage.vue'

import Signin from '@pages/Auth/Signin.vue'
import Signup from '@pages/Auth/Signup.vue'
import MessageAuth from '@pages/Auth/MessageAuth.vue'
import ResetPassword from '@pages/Auth/ResetPassword.vue'
import NewPass from '@pages/Auth/NewPass.vue'

import store from '@modules/index.js'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      component: DashboardLayout,
      redirect: '/main',
      children: [
        {
          path: 'main',
          name: 'mainPage',
          component: MainPage,
          meta: {
            requiresAuth: true,
            description: 'To-do List'
          }
        },
        {
          path: 'profile',
          name: 'userProfile',
          component: UserProfile,
          meta: {
            requiresAuth: true,
            description: 'User profile'
          }
        },
        {
          path: 'about',
          name: 'aboutPage',
          component: AboutPage,
          meta: {
            requiresAuth: true,
            description: 'About'
          }
        }
      ]
    },
    {
      path: '/signin',
      name: 'signin',
      component: Signin
    },
    {
      path: '/signup',
      name: 'signup',
      component: Signup
    },
    {
      path: '/password/reset',
      name: 'resetPassword',
      component: ResetPassword
    },
    {
      path: '/password/new/:token',
      name: 'newPass',
      component: NewPass
    },
    {
      path: '/message/auth',
      name: 'messageAuth',
      component: MessageAuth
    },
    { path: '*', component: NotFound }
  ],
  linkActiveClass: 'nav-item active',
  scrollBehavior: (to) => {
    if (to.hash) {
      return { selector: to.hash }
    } else {
      return { x: 0, y: 0 }
    }
  }
})

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (store.getters.isAuthenticated) {
      next()
      return
    }
    next({ name: 'signin' })
  } else {
    next()
  }
})

router.beforeResolve((to, from, next) => {
  if (to.name) {
    NProgress.start()
  }
  next()
})

router.afterEach((to, from) => {
  NProgress.done()
})

export default router
