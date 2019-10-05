<template>
  <div class="my-login-page">
    <section class="h-100">
      <div class="container h-100">
        <div class="row justify-content-md-center h-100">
          <div class="card-wrapper">
            <br>
            <div class="card fat">
              <div class="m-2 card-body">
                <p
                  v-for="value in messageAuth"
                  :key="value.id"
                >
                  {{ value }}
                </p>
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
  name: 'MessageAuth',

  computed: {
    messageAuth () {
      return this.$store.getters.messageAuth
    }
  }
}
</script>
