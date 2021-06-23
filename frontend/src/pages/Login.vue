<template>
    <div id="page-login">
        <div class="box-login-form">
            <form v-on:submit.prevent="login()" class="form w-100">
                <img src="/static/images/piperun-logo.png" height="50px" class="image-login" /> 
                <div class="form-group form-group-first" :class="{ 'form-group-add-error' : errors.email }">
                    <label for="email">Usuário</label>
                    <input type="email" class="form-control" id="email" v-model="user.email" placeholder="Digite seu e-mail aqui" />
                    <small v-if="errors.email" class="form-text text-muted">{{errors.email}}</small>
                </div>
                <div class="form-group form-group-first">
                    <label for="password">Senha</label>
                    <input type="password" class="form-control" id="password" v-model="user.password" placeholder="Digite sua senha aqui" />
                    <small v-if="errors.password" class="form-text text-muted">{{errors.password[0]}}</small>
                </div>
                <div class="buttons">
                    <button type="submit" class="btn btn-color w-100">Entrar <i v-if="loading" class="fal fa-spinner-third icon-loading"></i></button>
                </div>
                <hr>
                <div class="buttons">
                    <router-link to="/home" class="btn w-100">Tela inicial</router-link>
                </div>
            </form>
        </div>
        <div class="banner-login"></div>
    </div>
</template>

<script>

    import { api } from '@/services/api.ts';

    export default {
        name: 'Login',
        data(){
            return {
                loading: false,
                user:{
                    email: 'jorge@piperun.com',
                    password: 'password',
                },
                errors:{
                    email: '',
                    password: '',
                }
            }
        },
        methods:{
            login(){
                this.errors.email = '';
                // INICIAR LOADING
                this.loading = true;
                // MÉTODO AXIOS
                api.post(`login`, 
                {email: this.user.email,password: this.user.password})
                .then(response => {
                    console.log(response);
                    if(response.data.token){
                        //GUARDAR SESSÃO DO USUÁRIO
                        localStorage.setItem('auth', JSON.stringify(response.data)); 
                        // NOTIFICAÇÃO
                        this.$notify({
                            closeOnClick: true,
                            type: 'success',
                            text: 'Login realizado com sucesso!',
                            position: 'top right'
                        });
                        //ENCAMINHAR PARA TELA DO DASHBOARD
                        this.$router.push('/dashboard'); 
                        // REMOVER LOADING
                        this.loading = false;
                    }else{
                        // REMOVER LOADING
                        this.loading = false;
                        // RETORNO
                        this.errors.email = 'Usuário ou senha não compatíveis';
                    }
                })
                .catch(e => {
                    // REMOVER LOADING
                    this.loading = false;
                    this.errors.email = 'Usuário ou senha não compatíveis';
                })
            }
        }
    }

</script>

<style scoped>

    #page-login{
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    .box-login-form {
        height: 100vh;
        margin-left: 0px;
        width: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0px 30px;
    }

    .banner-login {
        flex: 1;
        height: 100vh;
        background: url('/static/images/banner-login.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }

    .image-login{
        display: block;
        margin: 0px auto 10px auto;
    }

</style>