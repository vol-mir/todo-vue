<template>
  <card>
    <h4 slot="header" class="card-title">Edit Password</h4>
    <form>
      <div class="row">
        <div class="col-md-12">
          <base-input type="password"
                    label="Password"
                    placeholder="Password"
                    v-model="password">
          </base-input>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <base-input type="password"
                      label="Password Confirmation"
                      placeholder="Password Confirmation"
                      v-model="passwordConfirmation">
          </base-input>
        </div>
      </div>

      <div class="text-center">
        <button
          type="submit"
          class="btn btn-info btn-fill float-right"
          :disabled="!hasChanges"
          @click.prevent="updatePassword"
        >
          Update Password
        </button>
      </div>
      <div class="clearfix"></div>
    </form>
  </card>
</template>
<script>
import Card from '@components/Cards/Card.vue'

export default {
  name: 'EditPasswordForm',

  components: {
    Card
  },

  data () {
    return {
      password: null,
      passwordConfirmation: null
    }
  },

  computed: {
    hasChanges () {
      return !(_.isEmpty(this.passwordConfirmation)) && !(_.isEmpty(this.password)) && (this.passwordConfirmation === this.password)
    }
  },

  methods: {
    updatePassword () {
      if (!this.hasChanges) {
        return
      }

      this.$store.dispatch('updatePassword', {
        'password': this.password,
        'passwordConfirmation': this.passwordConfirmation
      })
        .then(() => {
          this.password = null
          this.passwordConfirmation = null
        }).catch(error => {
          console.error(error)
        })
    }
  }
}
</script>
