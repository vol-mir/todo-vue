<template>
  <div class="my-login-page">
    <section class="h-100">
      <div class="container h-100">
        <div class="row justify-content-md-center h-100">
          <div class="card-wrapper">
            <div class="brand">
              <img
                src="/img/todo-logo.png"
                alt="logo"
              >
            </div>
            <div class="card fat">
              <div class="card-body">
                <h4 class="card-title">Login</h4>
                <form
                  method="POST"
                  class="my-login-validation"
                  novalidate=""
                  @submit.prevent="signin"
                >
                  <div class="form-group">
                    <label for="email">E-Mail Address</label>
                    <input
                      id="email"
                      type="email"
                      class="form-control"
                      name="email"
                      v-model="email"
                      required
                      autofocus
                    >
                    <div class="invalid-feedback">
                      Email is invalid
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="password">Password
                      <router-link
                        to="/password/reset"
                        class="float-right"
                      >
                        Forgot Password?
                      </router-link>
                    </label>
                    <input
                      id="password"
                      type="password"
                      class="form-control"
                      name="password"
                      v-model="password"
                      required
                      data-eye
                    >
                    <div class="invalid-feedback">
                      Password is required
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="custom-checkbox custom-control">
                      <input
                        type="checkbox"
                        name="remember"
                        id="remember"
                        v-model="remember"
                        class="custom-control-input"
                      >
                      <label
                        for="remember"
                        class="custom-control-label"
                      >
                        Remeber Me
                      </label>
                    </div>
                  </div>

                  <div class="form-group m-0">
                    <button
                      type="submit"
                      class="btn btn-primary btn-fill btn-block"
                    >
                      Login
                    </button>
                  </div>
                  <div class="mt-4 text-center">
                    Don't have an account? <router-link to="/signup">Create One</router-link>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import '@/scripts/my-login.js'
import Vue from 'vue'

export default {
  name: 'Signin',

  data () {
    return {
      email: '',
      password: '',
      remember: ''
    }
  },

  computed: {
    isDisabled () {
      return this.email.length === 0 || this.password.length === 0
    }
  },

  mounted () {
    let signupEmail = Vue.localStorage.get('signupEmail', '')
    let signupPassword = Vue.localStorage.get('signupPassword', '')
    Vue.localStorage.remove('signupEmail')
    Vue.localStorage.remove('signupPassword')

    if (signupEmail.length === 0 || signupPassword === 0) {
      return
    }

    this.$store.dispatch('signin', {
      email: signupEmail,
      password: signupPassword,
      remember_me: true
    }).then(() => {
      this.$router.push({ name: 'mainPage' })
    }).catch(error => {
      if (error) {
        this.$noty.error('Error: ' + error)
      }
    })
  },

  methods: {
    signin () {
      if (this.isDisabled) {
        return
      }

      this.$store.dispatch('signin', {
        email: this.email,
        password: this.password,
        remember_me: this.remember
      }).then(() => {
        this.$router.push({ name: 'mainPage' })
      }).catch(error => {
        if (error) {
          this.$noty.error('Error: ' + error)
        }
      })
    }
  }
}
</script>
