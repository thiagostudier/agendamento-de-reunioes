<template>
    <div id="meeting">
        <h2 class="date">{{meeting.date}} | {{meeting.start}} - {{meeting.end}}</h2>
        <h3 class="author">{{meeting.name}}<span v-if="auth"> - {{meeting.email}}</span></h3>
        <p v-if="auth" class="subject">{{meeting.subject}}</p>
        <!-- ATIVAR/DESATIVAR REUNIÃO -->
        <div v-if="auth" id="status-meeting">
            <button v-if="!status" v-on:click="updateStatus(meeting.id, true)"><i class="icon-accept fa fa-check" aria-hidden="true"></i> Aceitar</button>
            <button v-if="status" v-on:click="updateStatus(meeting.id, false)"><i class="icon-refuse fa fa-times" aria-hidden="true"></i> Recusar</button>
        </div>
        <!-- BOTÃO DE EDITAR -->
        <div v-if="auth" id="edit">
            <router-link :to="'/update-meeting/'+meeting.id"><i class="icon-edit fa fa-pencil" aria-hidden="true"></i> Editar</router-link>
        </div>
        <!-- SE FOR UM AGENDAMENTO NOVO -->
        <div id="if-new" v-if="new_meeting">
            <span>Novo!</span>
        </div>
    </div>
</template>

<script>

import { api } from '@/services/api.ts';

export default {
    name: 'Meeting',
    props:['meeting', 'auth'],
    data(){
        return{
            status: this.meeting.status,
            new_meeting: this.meeting.new
        }
    },
    created(){
        console.log(this.auth);
    },
    methods: {
        updateStatus(id, status){
            // MÉTODO AXIOS - ATUALIZAR REUNIÃO
            api.put(`meetings-accept/`+id, 
            {status: status})
            .then(response => {
                // NOTIFICAÇÃO
                this.$notify({
                    closeOnClick: true,
                    type: 'success',
                    text: 'Ação realizada com sucesso!',
                    position: 'top right'
                });
                this.status = status;
                this.new_meeting = false;
            })
            .catch(e => {
                // NOTIFICAÇÃO
                this.$notify({
                    closeOnClick: true,
                    type: 'error',
                    text: 'Houve algum erro ao realizar a ação!',
                    position: 'top right'
                });
            });
        },
    }
}
</script>

<style scoped>

#meeting{
    margin-bottom: 15px;
    border-left: 10px solid #FF5252 !important;
    padding: 5px 20px;
    border: 1px solid #f3f3f3;
    position: relative;
}

.date,
.author,
.subject{font-family: rubik,Sans-serif;margin-bottom: 0px;}

.date{font-size: 25px;font-weight: 700;margin-bottom: 5px;}
.author{font-size: 20px;margin-bottom: 2px;}
.subject{font-size: 14px;}

#status-meeting{
    position: absolute;
    top: 0px;
    right: 0px;
}

#status-meeting button {
    background: #fff;
    border: none;
    font-size: 14px;
    font-family: rubik,Sans-serif;
    font-weight: 500;
    color: #8e8282;
    cursor: pointer;
}

.icon-accept{color: #16b316;}
.icon-refuse{color: red}

#if-new{
    position: absolute;
    bottom: 0px;
    right: 0px;
    padding: 0px 4px;
}

#if-new span{
    font-family: rubik,Sans-serif;
    font-weight: 700;
}

#edit{
    position: absolute;
    top: 25px;
    right: 11px;
    padding: 0px 4px;
}

#edit a{
    background: #fff;
    border: none;
    font-size: 14px;
    font-family: rubik,Sans-serif;
    font-weight: 500;
    color: #8e8282;
    cursor: pointer;
    text-decoration: none;
}

</style>
