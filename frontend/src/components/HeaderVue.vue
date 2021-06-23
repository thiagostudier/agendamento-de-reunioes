<template>
  <header>
    <div class="container">
      <img src="/static/images/piperun-logo.png" /> 
      <div class="buttons-header">
        <router-link class="btn btn-login" to="/home">Tela Inicial</router-link>
        <span v-if="auth">olá, {{auth.name}}</span>
        <router-link v-if="!auth" class="btn btn-color btn-login" to="/login">Login</router-link>
        <router-link v-if="auth && this.$route.name != 'Dashboard'" class="btn btn-color btn-login" to="/dashboard">Dashboard</router-link>
        <button v-if="auth && this.$route.name == 'Dashboard'" class="btn btn-color btn-login" @click="loggout">Sair</button>
      </div>
    </div>
  </header>
</template>

<script>

export default {
  name: 'HeaderVue',
  data(){
    return{
      auth: JSON.parse(localStorage.getItem('auth')),
    }
  },
  methods: {
    loggout(){
      localStorage.clear();
      this.$router.push('/login');
    },
  }
}
</script>

<style scoped>

  header{
    padding: 15px 0px;
  }

  img{
    height: 50px;
  }

  .buttons-header{
    margin-left: auto;
  }

  div.container{
    display: flex;
    align-items: center;
  }

  span{
    font-family: rubik,Sans-serif;
    padding: 12px 24px;
  }

</style>
