<template>
  <div :class="{'nav-open': $sidebar.showSidebar}">
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: 'App'
}
</script>

<style lang="scss">
</style>
