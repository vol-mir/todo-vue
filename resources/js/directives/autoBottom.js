module.exports = function (Vue) {
  Vue.directive('auto-bottom', {
    update: function () {
      this.el.scrollTop = this.el.scrollHeight
    }
  })
}
