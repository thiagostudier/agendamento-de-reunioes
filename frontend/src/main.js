import Vue from 'vue'
import App from './App'
import router from './router'
import VCalendar from 'v-calendar';
import Notifications from 'vue-notification'
import FullCalendar from 'vue-full-calendar'

Vue.config.productionTip = false

Vue.use(VCalendar);
Vue.use(Notifications)
Vue.use(FullCalendar)

new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
