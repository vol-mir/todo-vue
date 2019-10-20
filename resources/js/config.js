let apiUrl = ''
let folderAvatars = '/img/avatars/'
let defaultAvatar = folderAvatars + 'default.png'

switch (process.env.NODE_ENV) {
  case 'development':
    apiUrl = 'http://todo-vue/api/v1'
    break
  case 'production':
    apiUrl = 'http://todo-vue.com/api/v1'
    break
}

export const APP_CONFIG = {
  API_URL: apiUrl,
  FOLDER_AVATARS: folderAvatars,
  DEFAULT_AVATAR: defaultAvatar
}
