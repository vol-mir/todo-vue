<template>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <card>
            <h4 slot="header" class="card-title">About Project "To-do list"</h4>
            <div class="typo-line">
              <div class="title-about">Stack:</div>
              <div class="stack">
                — PHP 7.1.3 - Hypertext Preprocessor (or simply PHP) is a general-purpose programming language originally designed for web development.<br>
                — Laravel 5.8 - Laravel is a web application framework with expressive, elegant syntax.<br>
                — Vue 2.5.17 - Vue.js is an open-source Model–view–viewmodel JavaScript framework for building user interfaces and single-page applications.<br>
              </div>

              <div class="title-about">Quick Start:</div>
              <div class="console">
                $ git clone https://github.com/vol-mir/todo-vue.git<br>
                $ cd todo-vue<br>
                $ composer install<br>
                $ cp .env.example .env<br>
                $ php artisan key:generate<br>
                $ php artisan migrate:fresh --seed<br>
                $ php artisan passport:install<br>
                $ npm install <br>
              </div>

              <div class="title-about">Test:</div>
              <div class="console">
                $ cp .env.testing .env<br>
                $ php vendor/phpunit/phpunit/phpunit<br>
              </div>

              <div class="title-about">
                <div class="container-fluid">
                  <div class="row">
                    <img class="icon-git" src="/img/fluidicon.png" alt="GitHub">
                    <a href="https://github.com/vol-mir/todo-vue">GitHub</a>
                  </div>
                </div>
              </div>

            </div>
          </card>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Card from '@components/Cards/Card.vue'

export default {
  name: 'AboutPage',

  components: {
    Card
  }
}
</script>
