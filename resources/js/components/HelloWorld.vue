<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Hello World Component</div>

                    <div class="card-body">
                        Hello World from Vue
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  mounted () {
    console.log('Component mounted.')
  }
}
</script>

<style>
    .container {
        font-family: 'Avenir', Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;
        margin-top: 60px;
    }
</style>
