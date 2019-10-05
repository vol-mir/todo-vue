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
                <h4 class="card-title">Reset password</h4>
                <form
                  method="POST"
                  class="my-login-validation"
                  novalidate=""
                  @submit.prevent="resetPassword"
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

                  <div class="form-group m-0">
                    <button
                      type="submit"
                      class="btn btn-primary btn-fill btn-block"
                    >
                      Reset your password
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
  name: 'ResetPassword',

  data () {
    return {
      email: ''
    }
  },

  computed: {
    isDisabled () {
      return this.email.length === 0
    }
  },

  methods: {
    resetPassword () {
      if (this.isDisabled) {
        return
      }

      this.$store.dispatch('resetPassword', {
        email: this.email
      }).then(() => {
        this.$store.commit('setMessageAuth', {
          p1: 'You successfully reset your password.',
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
