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
                <h4 class="card-title">New Password</h4>
                <form
                  method="POST"
                  class="my-login-validation"
                  @submit.prevent="newPass"
                  novalidate=""
                >
                  <div class="form-group">
                    <label for="password">New Password</label>
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
                      Submit
                    </button>
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
export default {
  name: 'NewPass',

  data () {
    return {
      email: '',
      token: '',
      password: '',
      password_confirmation: ''
    }
  },

  computed: {
    isDisabled () {
      return this.password.length === 0 || this.password.length !== this.password_confirmation.length
    }
  },

  methods: {
    newPass () {
      this.token = this.$route.params.token
      this.email = this.$route.query.email

      if (this.isDisabled) {
        return
      }

      this.$store.dispatch('createPassword', {
        email: this.email,
        token: this.token,
        password: this.password,
        password_confirmation: this.password_confirmation
      }).then(() => {
        this.$store.dispatch('signin', {
          email: this.email,
          password: this.password,
          remember_me: false
        }).then(() => {
          this.$router.push({ name: 'mainPage' })
        }).catch(error => {
          if (error) {
            this.$noty.error('Error: ' + error)
          }
        })
      }).catch(error => {
        if (error) {
          this.$noty.error('Error: ' + error)
        }
      })
    }
  }
}
</script>
