<template>
  <card>
    <h4 slot="header" class="card-title">Edit Avatar</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="text-center">
          <div v-if="this.imageData" class="cropper-wrapper">
            <div :style="{backgroundImage: 'url(' + imageData + ')'}" class="cropper-background"></div>
            <cropper
              classname="cropper"
              :src="imageData"
              :stencilProps="{
                minAspectRatio: 8/8,
                maxAspectRatio: 10/10
              }"
              @change="onCrop"
            ></cropper>
          </div>
          <avatar v-else class="avatar-cent border-gray" :username="this.fullName" :size="120" :src="this.fileAvatar"></avatar>
          <div class="button-wrapper">
            <span class="btn btn-default btn-fill" @click="$refs.file.click()">
              <input type="file" ref="file" @change="uploadImage($event)" accept="image/*">
              Upload image
            </span>
            <span class="horizont-separator"></span>
            <button
              type="submit"
              :disabled="!hasChanges"
              class="btn btn-info btn-fill"
              @click.prevent="updateAvatar"
            >
              Update Avatar
            </button>
            <span class="horizont-separator"></span>
            <button
              type="submit"
              class="btn btn-danger btn-fill"
              @click.prevent="clearAvatar"
            >
              Clear Avatar
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
  </card>
</template>

<script>
import Avatar from 'vue-avatar'
import Card from '@components/Cards/Card.vue'
import { Cropper } from 'vue-advanced-cropper'
import { APP_CONFIG } from '@/config.js'

export default {
  name: 'EditAvatar',

  components: {
    Avatar,
    Cropper,
    Card
  },

  data () {
    return {
      imageData: null,
      cropImage: null
    }
  },

  computed: {
    hasChanges () {
      return !(_.isEmpty(this.imageData))
    },

    storeUser () {
      return this.$store.getters.getUser
    },

    fullName () {
      let strFullName = this.storeUser.firstName ? this.storeUser.firstName : ''
      strFullName = this.storeUser.lastName ? strFullName + ' ' + this.storeUser.lastName : ''
      return strFullName
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
    onCrop ({ canvas }) {
      if (!this.hasChanges) return
      this.cropImage = canvas.toDataURL()
    },

    updateAvatar () {
      this.$store.dispatch('updateAvatar', {
        'avatar': this.cropImage
      })
        .then(() => {
          this.imageData = null
          this.cropImage = null
          this.$refs.file.value = ''
        }).catch(error => {
          console.error(error)
        })
    },

    uploadImage (event) {
      let input = event.target
      if (input.files && input.files[0]) {
        let reader = new FileReader()
        reader.onload = (e) => {
          this.imageData = e.target.result
        }
        reader.readAsDataURL(input.files[0])
      }
    },

    clearAvatar () {
      this.$store.dispatch('updateAvatar', {
        'avatar': ''
      })
        .then(() => {
          this.imageData = null
          this.cropImage = null
          this.$refs.file.value = ''
        }).catch(error => {
          console.error(error)
        })
    }
  }
}
</script>

<style scoped>
  .cropper {
    height: 400px;
    max-width: 400px;
  }

  .cropper-wrapper {
    overflow: hidden;
    margin: auto;
    position: relative;
    z-index: 105;
    max-width: 400px;
    position: relative;
    height: 400px;
    background: #DDD;
  }
  .cropper-background {
    position: absolute;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: 50%;
    filter: blur(5px);
    opacity: 0.25;
  }

  .button-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 17px;
  }

  .btn input {
    display: none;
  }

  .horizont-separator {
    margin-right: 10px;
  }
</style>
