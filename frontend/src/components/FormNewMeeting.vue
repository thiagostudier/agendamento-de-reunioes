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
                <div class="form-group col-12 col-sm" :class="{ 'form-group-add-error' : errors.date }">
                    <label for="date">Data da reunião</label>
                    <input type="date" class="form-control" id="date" v-model="newMeeting.date" />
                    <small v-if="errors.date" class="form-text text-muted">{{errors.date[0]}}</small>
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
            <button v-if="!this.edit" class="btn btn-send d-block ml-auto" v-on:click="createMeeting()">Agendar</button>
            <button v-if="this.edit" class="btn btn-send d-block ml-auto" v-on:click="updateMeeting()">Editar</button>
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
    props:['results'],
    data(){
        return{
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
            },
        }
    },
    created(){
        if(this.$route.params.id){
            this.getMeeting(this.$route.params.id);
        }
    },
    methods: {
        createMeeting(){
            // MÉTODO AXIOS - CRIAR REUNIÃO
            api.post(`meetings`, 
            {name: this.newMeeting.name, email: this.newMeeting.email, subject: this.newMeeting.subject, date: this.newMeeting.date, start: this.newMeeting.start, end: this.newMeeting.end})
            .then(response => {
                // SE HOUVER ERRO NO CADASTRO
                if(response.data.errors){
                    this.errors = response.data.errors;
                }else{
                    // NOTIFICAÇÃO
                    this.$notify({
                        closeOnClick: true,
                        type: 'success',
                        text: 'Reunião cadastrada com sucesso!',
                        position: 'top right'
                    });
                    // LIMPAR DADOS
                    this.newMeeting.name = null;
                    this.newMeeting.email = null;
                    this.newMeeting.subject = null;
                    this.newMeeting.date = null;
                    this.newMeeting.start = null;
                    this.newMeeting.end = null;
                }
            })
            .catch(e => {
                console.log(e);
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
            // MÉTODO AXIOS - CRIAR REUNIÃO
            api.put(`meetings/`+this.$route.params.id, 
            {name: this.newMeeting.name, email: this.newMeeting.email, subject: this.newMeeting.subject, date: this.newMeeting.date, start: this.newMeeting.start, end: this.newMeeting.end})
            .then(response => {
                // SE HOUVER ERRO NO CADASTRO
                if(response.data.errors){
                    this.errors = response.data.errors;
                }else{
                    // NOTIFICAÇÃO
                    this.$notify({
                        closeOnClick: true,
                        type: 'success',
                        text: 'Reunião cadastrada com sucesso!',
                        position: 'top right'
                    });
                    // RETORNAR PARA O DASHBOARD
                    this.$router.push('/dashboard');
                }
                
            })
            .catch(e => {
                console.log(e);
                // NOTIFICAÇÃO
                this.$notify({
                    closeOnClick: true,
                    type: 'error',
                    text: 'Houve algum erro ao cadastrar a reunião!',
                    position: 'top right'
                });
            });
        },
        getMeeting(id){
            // MÉTODO AXIOS - PEGAR REUNIÕES
            api.get(`meetings/`+id)
            .then(response => {
                // PEGAR OS DADOS DA REUNIÃO
                this.edit = true;
                this.newMeeting.name = response.data.name;
                this.newMeeting.email = response.data.email;
                this.newMeeting.subject = response.data.subject;
                this.newMeeting.date = response.data.date;
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
        }
    }
}
</script>

<style scoped>



</style>