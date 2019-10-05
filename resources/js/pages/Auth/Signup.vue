<template>
    <div class="my-login-page">
      <section class="h-100">
        <div class="container h-100">
          <div class="row justify-content-md-center h-100">
            <div class="card-wrapper">
              <div class="brand">
                <img src="/img/todo-logo.png" alt="logo">
              </div>
              <div class="card fat">
                <div class="card-body">
                  <h4 class="card-title">Register</h4>
                  <form
                    method="POST"
                    class="my-login-validation"
                    @submit.prevent="signup"
                    novalidate=""
                  >
                    <div class="form-group">
                      <label for="name">Name</label>
                      <input
                        id="name"
                        type="text"
                        class="form-control"
                        name="name"
                        v-model="username"
                        required
                        autofocus
                      >
                      <div class="invalid-feedback">
                        What's your name?
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="email">E-Mail Address</label>
                      <input
                        id="email"
                        type="email"
                        class="form-control"
                        name="email"
                        v-model="email"
                        required
                      >
                      <div class="invalid-feedback">
                        Your email is invalid
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="password">Password</label>
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
                      <label for="password_confirmation">Password Confirmation</label>
                      <input
                        id="password_confirmation"
                        type="password"
                        class="form-control"
                        name="password"
                        v-model="password_confirmation"
                        required
                        data-eye
                      >
                      <div class="invalid-feedback">
                        Password is required
                      </div>
                    </div>

                    <div class="form-group m-0">
                      <button
                        type="submit"
                        class="btn btn-primary btn-fill btn-block"
                        :disabled=isDisabled
                      >
                        Register
                      </button>
                    </div>
                    <div class="mt-4 text-center">
                      Already have an account? <router-link to="/signin">Login</router-link>
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

export default {
  name: 'Signup',

  data () {
    return {
      username: '',
      email: '',
      password: '',
      password_confirmation: ''
    }
  },

  computed: {
    isDisabled () {
      return this.username.length === 0 || this.email.length === 0 || this.password.length === 0 || this.password.length !== this.password_confirmation.length
    }
  },

  methods: {
    signup () {
      if (this.isDisabled) {
        return
      }

      this.$store.dispatch('signup', {
        name: this.username,
        email: this.email,
        password: this.password,
        password_confirmation: this.password_confirmation
      }).then(() => {
        this.$store.commit('setMessageAuth', {
          p1: 'You successfully created your account.',
          p2: 'Please validate your account with the email we\'ve just sent to you'
        })
        this.$router.push({ name: 'messageAuth' })
      }).catch(error => {
        if (error) {
          this.$noty.error('Error: ' + error)
        }
      })
    }
  }
}
</script>
