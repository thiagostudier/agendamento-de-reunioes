<template>
    <div>
        <h1 v-if="!this.edit" class="title">Nova reunião</h1>
        <h1 v-if="this.edit" class="title">Editar reunião</h1>
        <div class="form">
            <div class="row">
                <!-- NOME -->
                <div class="form-group col-12 col-md" :class="{ 'form-group-add-error' : errors.name }">
                    <label for="name">Informe o seu nome</label>
                    <input type="text" class="form-control" id="name" v-model="newMeeting.name" />
                    <small v-if="errors.name" class="form-text text-muted">{{errors.name[0]}}</small>
                </div>
                <!-- E-MAIL -->
                <div class="form-group col-12 col-md" :class="{ 'form-group-add-error' : errors.email }">
                    <label for="email">Informe o seu e-mail</label>
                    <input type="email" class="form-control" id="email" v-model="newMeeting.email" />
                    <small v-if="errors.email" class="form-text text-muted">{{errors.email[0]}}</small>
                </div>
                <!-- ASSUNTO -->
                <div class="form-group col-12 col-md" :class="{ 'form-group-add-error' : errors.subject }">
                    <label for="subject">Informe a pauta da reunião</label>
                    <input type="text" class="form-control" id="subject" v-model="newMeeting.subject" />
                    <small v-if="errors.subject" class="form-text text-muted">{{errors.subject[0]}}</small>
                </div>
            </div>
            <div class="row">
                <!-- DATA -->
                <div class="form-group col-12 col-sm" :class="{ 'form-group-add-error' : errors.date || errors.week }">
                    <label for="date">Data da reunião</label>
                    <input type="date" class="form-control" id="date" v-model="newMeeting.date" />
                    <small v-if="errors.date" class="form-text text-muted">{{errors.date[0]}}</small>
                    <small v-else-if="errors.week" class="form-text text-muted">{{errors.week[0]}}</small>
                </div>
                <!-- INÍCIO -->
                <div class="form-group col-12 col-sm" :class="{ 'form-group-add-error' : errors.start }">
                    <label for="start">Horário inicial</label>
                    <the-mask type="text" masked :mask="'##:##'" class="form-control" id="start" v-model="newMeeting.start" placeholder="00:00"></the-mask>
                    <small v-if="errors.start" class="form-text text-muted">{{errors.start[0]}}</small>
                </div>
                <!-- FINAL -->
                <div class="form-group col-12 col-sm" :class="{ 'form-group-add-error' : errors.end }">
                    <label for="end">Horário final</label>
                    <the-mask type="text" masked :mask="'##:##'" class="form-control" id="end" v-model="newMeeting.end" placeholder="00:00"></the-mask>
                    <small v-if="errors.end" class="form-text text-muted">{{errors.end[0]}}</small>
                </div>
            </div>
            <div class="d-flex">
                <!-- CRIAR -->
                <button v-if="!this.edit" class="btn btn-send d-block ml-auto" v-on:click="createMeeting()">Agendar</button>
                <!-- REMOVER -->
                <button v-if="this.edit" class="btn btn-remove d-block mr-auto" v-on:click="removeMeeting()">Apagar</button>
                <!-- ALTERAR -->
                <button v-if="this.edit" class="btn btn-send d-block ml-auto" v-on:click="updateMeeting()">Editar</button>
            </div>
        </div>

    </div>

</template>

<script>

import { TheMask } from 'vue-the-mask';
import { api } from '@/services/api.ts';

export default {
    name: 'FormNewMeeting',
    components:{
        TheMask
    },
    props: ['id'],
    data(){
        return{
            auth: JSON.parse(localStorage.getItem('auth')),
            edit: false,
            selectedDate: null,
            newMeeting:{
                name: null,
                email: null,
                subject: null,
                date: null,
                start: null,
                end: null,
            },
            errors:{
                name: null,
                date: null,
                subject: null,
                email: null,
                start: null,
                end: null,
                week: null,
            },
        }
    },
    created(){
        if(this.id){
            this.getMeeting();
        }
    },
    methods: {
        createMeeting(){
            // LIMPAR ERROS
            this.clearErrors(); 
            // MÉTODO AXIOS - CRIAR REUNIÃO
            api.post(`meetings`, 
            {
                name: this.newMeeting.name, 
                email: this.newMeeting.email, 
                subject: this.newMeeting.subject, 
                date: this.newMeeting.date, 
                start: this.newMeeting.start, 
                end: this.newMeeting.end
            })
            .then(response => {
                if(response.status == 201){
                    // NOTIFICAÇÃO
                    this.$notify({
                        closeOnClick: true,
                        type: 'success',
                        text: 'A solicitação de reunião foi enviada com sucesso!',
                        position: 'top right'
                    });
                    // LIMPAR ERROS
                    this.clearErrors();
                    // LIMPAR DADOS
                    this.newMeeting.name = null;
                    this.newMeeting.email = null;
                    this.newMeeting.subject = null;
                    this.newMeeting.date = null;
                    this.newMeeting.start = null;
                    this.newMeeting.end = null;
                }else{
                    // NOTIFICAÇÃO
                    this.$notify({
                        closeOnClick: true,
                        type: 'error',
                        text: 'Houve algum erro ao cadastrar a reunião!',
                        position: 'top right'
                    });
                }
            })
            .catch(e => {
                // PEGAR OS ERROS
                this.errors = e.response.data.errors;
                // NOTIFICAÇÃO
                this.$notify({
                    closeOnClick: true,
                    type: 'error',
                    text: 'Houve algum erro ao cadastrar a reunião!',
                    position: 'top right'
                });
            });
        },
        updateMeeting(){
            // LIMPAR ERROS
            this.clearErrors();
            // MÉTODO AXIOS - EDITAR REUNIÃO
            api.patch(`meetings/`+this.$route.params.id, 
            {name: this.newMeeting.name, email: this.newMeeting.email, subject: this.newMeeting.subject, date: this.newMeeting.date, start: this.newMeeting.start, end: this.newMeeting.end},
            {headers: {"Authorization":"Bearer "+this.auth.token}})
            .then(response => {
                if(response.status == 200){
                    // NOTIFICAÇÃO
                    this.$notify({
                        closeOnClick: true,
                        type: 'success',
                        text: 'Reunião cadastrada com sucesso!',
                        position: 'top right'
                    });
                    // RETORNAR PARA O DASHBOARD
                    this.$router.push('/dashboard');
                }else{
                    // NOTIFICAÇÃO
                    this.$notify({
                        closeOnClick: true,
                        type: 'error',
                        text: 'Houve algum erro ao atualizar a reunião!',
                        position: 'top right'
                    });
                }
            })
            .catch(e => {
                // PEGAR OS ERROS
                this.errors = e.response.data.errors;
                // NOTIFICAÇÃO
                this.$notify({
                    closeOnClick: true,
                    type: 'error',
                    text: 'Houve algum erro ao atualizar a reunião!',
                    position: 'top right'
                });
            });
        },
        getMeeting(){
            // MÉTODO AXIOS - PEGAR REUNIÕES
            api.get(`meetings/`+this.$route.params.id)
            .then(response => {
                // PEGAR OS DADOS DA REUNIÃO
                this.edit = true;
                this.newMeeting.name = response.data.name;
                this.newMeeting.email = response.data.email;
                this.newMeeting.subject = response.data.subject;
                this.newMeeting.date = this.formatStringDate(response.data.date);
                this.newMeeting.start = response.data.start;
                this.newMeeting.end = response.data.end;
            })
            .catch(e => {
                // RETORNAR PARA O DASHBOARD
                this.$router.push('/dashboard');
                // NOTIFICAÇÃO
                this.$notify({
                    closeOnClick: true,
                    type: 'error',
                    text: 'Reunião não localizada!',
                    position: 'top right'
                });
            });
        },
        formatStringDate(data) {
            var dia  = data.split("/")[0];
            var mes  = data.split("/")[1];
            var ano  = data.split("/")[2];
            return ano + '-' + ("0"+mes).slice(-2) + '-' + ("0"+dia).slice(-2);
        },
        removeMeeting(){
            // MÉTODO AXIOS - REMOVER REUNIÃO
            api.delete(`meetings/`+this.$route.params.id,
            {headers: {"Authorization":"Bearer "+this.auth.token}})
            .then(response => {
                // SE HOUVER ERRO NA REMOÇÃO
                if(response.data.errors){
                    this.errors = response.data.errors;
                }else{
                    // NOTIFICAÇÃO
                    this.$notify({
                        closeOnClick: true,
                        type: 'success',
                        text: 'Reunião removida com sucesso!',
                        position: 'top right'
                    });
                    // RETORNAR PARA O DASHBOARD
                    this.$router.push('/dashboard');
                }
                
            })
            .catch(e => {
                // NOTIFICAÇÃO
                this.$notify({
                    closeOnClick: true,
                    type: 'error',
                    text: 'Houve algum erro ao cadastrar a reunião!',
                    position: 'top right'
                });
            });
        },
        clearErrors(){
            this.errors.name = null;
            this.errors.date = null;
            this.errors.subject = null;
            this.errors.email = null;
            this.errors.start = null;
            this.errors.week = null;
        }
    }
}
</script>

<style scoped>



</style>