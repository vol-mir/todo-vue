<template>
  <card class="card-user">
    <img slot="image" src="/img/bloknot.jpg" alt="..."/>
    <div class="author">
      <router-link :to="{ name: 'userProfile'}">
        <avatar class="avatar-cent border-gray" :username="this.fullName" :size="120" :src="this.fileAvatar"></avatar>
        <h4 v-if="this.fullName" class="title">{{ this.fullName }} <br />
          <small>{{ this.storeUser.name }}</small>
        </h4>
        <h4 v-else class="title">
          <small>{{ this.storeUser.name }}</small>
        </h4>
      </router-link>
    </div>
    <p v-if="this.place" class="description text-center">
      {{ this.place }}
    </p>
    <p v-if="this.storeUser.aboutMe" class="description text-center">
      "{{ this.storeUser.aboutMe }}"
    </p>
  </card>
</template>

<script>
import Avatar from 'vue-avatar'
import Card from '@components/Cards/Card.vue'
import { APP_CONFIG } from '@/config.js'

export default {
  name: 'UserCard',

  components: {
    Avatar,
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
    },

    place () {
      let strPlace = this.storeUser.city ? this.storeUser.city : ''
      strPlace = this.storeUser.country ? strPlace + ', ' + this.storeUser.country : ''
      return strPlace
    },

    fileAvatar () {
      if (!this.storeUser.avatar && !this.fullName) {
        return APP_CONFIG.DEFAULT_AVATAR
      }
      if (this.storeUser.avatar) {
        return APP_CONFIG.FOLDER_AVATARS + this.storeUser.avatar
      }
      return ''
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
