<template>
  <ul class="nav nav-mobile-menu">
    <li v-if="isAuthenticated" class="nav-item">
      <a class="nav-link" @click="logout">
        <span class="no-icon">Log out</span>
      </a>
    </li>
  </ul>
</template>

<script>
export default {
  name: 'mobile-menu',

  computed: {
    isAuthenticated () {
      return this.$store.getters.isAuthenticated
    }
  },

  methods: {
    logout () {
      this.$store.dispatch('logout')
        .then(() => {
          this.$router.push('/signin')
        })
    }
  }
}
</script>
