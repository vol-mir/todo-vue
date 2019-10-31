<template>
  <div class="wrapper">
    <side-bar>
      <mobile-menu slot="content"></mobile-menu>
      <sidebar-link to="/main">
        <i class="nc-icon nc-notes"></i>
        <p>To-do list</p>
      </sidebar-link>
      <sidebar-link to="/profile">
        <i class="nc-icon nc-circle-09"></i>
        <p>User Profile</p>
      </sidebar-link>
      <sidebar-link to="/about">
        <i class="nc-icon nc-paper-2"></i>
        <p>About</p>
      </sidebar-link>
      <template slot="bottom-links">
      </template>
    </side-bar>
    <div class="main-panel">
      <top-navbar></top-navbar>
      <dashboard-content @click="toggleSidebar">
      </dashboard-content>
      <content-footer></content-footer>
    </div>
  </div>
</template>

<script>
import TopNavbar from '@layout/TopNavbar.vue'
import ContentFooter from '@layout/ContentFooter.vue'
import DashboardContent from '@layout/Content.vue'
import MobileMenu from '@layout/MobileMenu.vue'

export default {
  components: {
    TopNavbar,
    ContentFooter,
    DashboardContent,
    MobileMenu
  },
  methods: {
    toggleSidebar () {
      if (this.$sidebar.showSidebar) {
        this.$sidebar.displaySidebar(false)
      }
    }
  }
}
</script>
