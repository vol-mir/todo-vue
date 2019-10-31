<template>
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <router-link :to="this.$route.path" class="navbar-brand">{{ this.$route.meta.description }}</router-link>
      <button type="button"
              class="navbar-toggler navbar-toggler-right"
              :class="{toggled: $sidebar.showSidebar}"
              aria-controls="navigation-index"
              aria-expanded="false"
              aria-label="Toggle navigation"
              @click="toggleSidebar">
        <span class="navbar-toggler-bar burger-lines"></span>
        <span class="navbar-toggler-bar burger-lines"></span>
        <span class="navbar-toggler-bar burger-lines"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav ml-auto">
          <li v-if="isAuthenticated" class="nav-item">
            <a @click="logout" class="nav-link">
              Log out
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  computed: {
    routeName () {
      const { name } = this.$route
      return this.capitalizeFirstLetter(name)
    },

    isAuthenticated () {
      return this.$store.getters.isAuthenticated
    }
  },

  data () {
    return {
      activeNotifications: false
    }
  },

  methods: {
    capitalizeFirstLetter (string) {
      return string.charAt(0).toUpperCase() + string.slice(1)
    },
    toggleNotificationDropDown () {
      this.activeNotifications = !this.activeNotifications
    },
    closeDropDown () {
      this.activeNotifications = false
    },
    toggleSidebar () {
      this.$sidebar.displaySidebar(!this.$sidebar.showSidebar)
    },
    hideSidebar () {
      this.$sidebar.displaySidebar(false)
    },

    logout () {
      this.$store.dispatch('logout')
        .then(() => {
          this.$router.push('/signin')
        })
    }
  }
}

</script>
