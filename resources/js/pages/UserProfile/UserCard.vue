<template>
  <card class="card-user">
    <img slot="image" src="/img/bloknot.jpg" alt="..."/>
    <div class="author">
      <router-link :to="{ name: 'userProfile'}">
        <img class="avatar border-gray" src="img/faces/face-3.jpg" alt="..."/>
        <h4 v-if="this.fullName" class="title">{{ this.fullName }} <br />
          <small>{{ this.storeUser.name }}</small>
        </h4>
        <h4 v-else class="title">
          <small>{{ this.storeUser.name }}</small>
        </h4>
      </router-link>
    </div>
    <p v-if="this.storeUser.aboutMe" class="description text-center">
      {{ this.storeUser.aboutMe }}
    </p>
  </card>
</template>

<script>
import Card from '@components/Cards/Card.vue'

export default {
  name: 'UserCard',

  components: {
    Card
  },

  computed: {
    storeUser () {
      return this.$store.getters.getUser
    },

    fullName () {
      let strFullName = this.storeUser.firstName ? this.storeUser.firstName : ''
      strFullName = this.storeUser.lastName ? strFullName + ' ' + this.storeUser.lastName : ''
      return strFullName
    }
  },

  methods: {
    getClasses (index) {
      let remainder = index % 3
      if (remainder === 0) {
        return 'col-md-3 col-md-offset-1'
      } else if (remainder === 2) {
        return 'col-md-4'
      } else {
        return 'col-md-3'
      }
    }
  }
}
</script>
