import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/pages/Home'
import Login from '@/pages/Login'
import Dashboard from '@/pages/Dashboard'
import UpdateMeeting from '@/pages/UpdateMeeting'
import user from '@/middleware/user';
import login from '@/middleware/login';

Vue.use(Router)

const router = new Router({
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home,
    },
    {
      path: '/login',
      name: 'Login',
      component: Login,
      meta: {
        requiresNotAuth: true,
      },
    },
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: Dashboard,
      meta: {
        requiresAuth: true,
      },
    },
    {
      path: '/update-meeting/:id',
      name: 'UpdateMeeting',
      props: true,
      component: UpdateMeeting,
      meta: {
        requiresAuth: true,
      },
    },
    {
      path: '*',
      component: Home,
    },
  ]
});

router.beforeEach((to, from, next) => {
  // PEGAR TOKEN NO LOCALSTORAGE
  var token = localStorage.getItem('auth') && JSON.parse(localStorage.getItem('auth')).token ? JSON.parse(localStorage.getItem('auth')).token : false;
  // SE FOR UMA ROTA COM AUTENTICÃO
  if (to.matched.some(record => record.meta.requiresAuth)){
    if(token){
      return next()
    }else{
      return next('/login')
    }
  // SE FOR UMA ROTA RESTRITA PARA QUEM NÃO ESTIVER AUTENTICADO
  }else if(to.matched.some(record => record.meta.requiresNotAuth)){
    if(!token){
      return next()
    }else{
      return next('/dashboard')
    }
  }else{
    return next()
  }
})

export default router;