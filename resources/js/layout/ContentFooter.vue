<template>
  <footer class="footer">
    <div class="container-fluid">
      <nav>
        <ul class="footer-menu">
          <li>
            <router-link :to="{path:'/'}">To Do List</router-link>
          </li>
        </ul>
      </nav>
      <div class="copyright text-center">
        &copy; Coded with by
        <a href="https://www.linkedin.com/in/vladimirbykovski/" target="_blank">Vladimir Bykovsky</a>.
      </div>
    </div>
  </footer>
</template>

<script>
export default {}
</script>
