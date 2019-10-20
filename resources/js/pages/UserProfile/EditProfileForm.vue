<template>
  <card>
    <h4 slot="header" class="card-title">Edit Profile</h4>
    <form>
      <div class="row">
        <div class="col-md-12">
          <base-input type="text"
                    label="Login"
                    placeholder="Login"
                    v-model="user.name">
          </base-input>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <base-input type="text"
                    label="First Name"
                    placeholder="First Name"
                    v-model="user.firstName">
          </base-input>
        </div>
        <div class="col-md-6">
          <base-input type="text"
                    label="Last Name"
                    placeholder="Last Name"
                    v-model="user.lastName">
          </base-input>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <base-input type="text"
                    label="City"
                    placeholder="City"
                    v-model="user.city">
          </base-input>
        </div>
        <div class="col-md-6">
          <base-input type="text"
                    label="Country"
                    placeholder="Country"
                    v-model="user.country">
          </base-input>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label>About Me</label>
            <textarea rows="5" class="form-control border-input"
                      placeholder="Here can be your description"
                      v-model="user.aboutMe">
              </textarea>
          </div>
        </div>
      </div>
      <div class="text-center">
        <button
          type="submit"
          class="btn btn-info btn-fill float-right"
          :disabled="!hasChanges"
          @click.prevent="updateProfile"
        >
          Update Profile
        </button>
      </div>
      <div class="clearfix"></div>
    </form>
  </card>
</template>
<script>
import { difference } from '@/extensions/Object+Difference'
import Card from '@components/Cards/Card.vue'

export default {
  name: 'EditProfileForm',

  components: {
    Card
  },

  data () {
    return {
      user: {}
    }
  },

  computed: {
    differ () {
      return difference(this.user, this.storeUser)
    },

    hasChanges () {
      return !(_.isEmpty(this.differ))
    },

    storeUser () {
      return this.$store.getters.getUser
    },

    isDisabled () {
      return this.user.name.length === 0
    }
  },

  mounted () {
    this.retrieveFromStore()
  },

  methods: {
    retrieveFromStore () {
      this.user = {
        name: this.storeUser.name,
        firstName: this.storeUser.firstName,
        lastName: this.storeUser.lastName,
        city: this.storeUser.city,
        country: this.storeUser.country,
        aboutMe: this.storeUser.aboutMe
      }
    },

    updateProfile () {
      if (this.isDisabled) {
        return
      }

      this.$store.dispatch('updateUser', this.differ)
        .then(() => {
          this.retrieveFromStore()
        }).catch(error => {
          console.error(error)
        })
    }
  }
}
</script>
