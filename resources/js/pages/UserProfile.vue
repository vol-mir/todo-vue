<template>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <edit-profile-form>
          </edit-profile-form>
        </div>
        <div class="col-md-4">
          <user-card>
          </user-card>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8">
          <edit-avatar>
          </edit-avatar>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8">
          <edit-password-form>
          </edit-password-form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import EditProfileForm from './UserProfile/EditProfileForm.vue'
import EditAvatar from './UserProfile/EditAvatar.vue'
import EditPasswordForm from './UserProfile/EditPasswordForm.vue'
import UserCard from './UserProfile/UserCard.vue'

export default {
  name: 'UserProfile',

  components: {
    EditProfileForm,
    EditAvatar,
    EditPasswordForm,
    UserCard
  }
}
</script>
