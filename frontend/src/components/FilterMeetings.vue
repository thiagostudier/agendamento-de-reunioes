<template>
    <div id="filter-meeting">
        <div class="d-flex">
            <h3 class="title">Filtrar reuniões</h3>
            <div class="ml-auto box-checkboxs">
                <!-- PEGAR APENAS REUNIÕES ACEITAS -->
                <label class="label-checkboxs" for="meetings-accept"><input type="checkbox" @change="filterMeetings()" v-model="filter.filteringMeetingsAccept" id="meetings-accept" /> Apenas reuniões aceitas</label>
                <!-- PEGAR APENAS REUNIÕES NOVAS -->
                <label class="label-checkboxs" for="new-meetings"><input type="checkbox" @change="filterMeetings()" v-model="filter.filteringNewMeetings" id="new-meetings" /> Apenas reuniões novas</label>
                <!-- PEGAR APENAS REUNIÕES QUE AINDA NÃO FORAM REALIZADAS  -->
                <label class="label-checkboxs" for="future-meetings"><input type="checkbox" @change="filterMeetings()" v-model="filter.futureMeetings" id="future-meetings" /> Apenas reuniões futuras</label>
            </div>
        </div>
        <form class="form" v-on:submit.prevent="filterMeetings()">
            <div class="row form-row">
                <!-- NOME -->
                <div class="form-group col-12 col-md">
                    <label>Nome</label>
                    <input type="text" class="form-control" v-model="filter.name" />
                </div>
                <!-- E-MAIL -->
                <div class="form-group col-12 col-md">
                    <label>E-mail</label>
                    <input type="email" class="form-control" v-model="filter.email" />
                </div>
                <!-- ASSUNTO -->
                <div class="form-group col-12 col-md">
                    <label>Pauta</label>
                    <input type="text" class="form-control" v-model="filter.subject" />
                </div>
                <!-- DATA INICIAL -->
                <div class="form-group col-12 col-sm" style="max-width: 182px !important;">
                    <label>Data inicial</label>
                    <input type="date" class="form-control" v-model="filter.date_start" />
                </div>
                <!-- DATA FINAL -->
                <div class="form-group col-12 col-sm" style="max-width: 182px !important;">
                    <label>Data final</label>
                    <input type="date" class="form-control" v-model="filter.date_end" />
                </div>
                <button class="btn btn-send btn-icon ml-auto d-block" @click="filterMeetings()"><i class="fa fa-search" aria-hidden="true"></i></button>
            </div>
        </form>
    </div>
</template>

<script>

import { TheMask } from 'vue-the-mask';
import { api } from '@/services/api.ts';

export default {
    name: 'FilterMeetings',
    components:{
        TheMask
    },
    data(){
        return{
            filter: {
                name: null,
                email: null,
                subject: null,
                date: null,
                date_start: null,
                date_end: null,
                start: null,
                end: null,
                futureMeetings: null,
                filteringNewMeetings: null,
                filteringMeetingsAccept: null,
            },
        }
    },
    props: {
        updateMeetings: { type: Function },
    },
    created(){
        // PEGAR DADOS DOS FILTROS EM LOCALHOST
        this.filter = JSON.parse(localStorage.getItem('filtering'));
        // FILTRAR DADOS
        this.filterMeetings(); 
    },
    methods:{
        filterMeetings(){
            // MÉTODO AXIOS - PEGAR REUNIÕES
            api.post(`filter`, 
            {name: this.filter.name, 
            email: this.filter.email, 
            subject: this.filter.subject, 
            date: this.filter.date, 
            date_start: this.filter.date_start, 
            date_end: this.filter.date_end, 
            start: this.filter.start, 
            end: this.filter.end, 
            futureMeetings: this.filter.futureMeetings, 
            filteringNewMeetings: this.filter.filteringNewMeetings, 
            filteringMeetingsAccept: this.filter.filteringMeetingsAccept})
            .then(response => {
                this.updateMeetings(response.data);
                localStorage.setItem('filtering', JSON.stringify(this.filter));
            })
            .catch(e => {
                // NOTIFICAÇÃO
                this.$notify({
                    closeOnClick: true,
                    type: 'error',
                    text: 'Houve algum erro ao buscar as reuniões cadastradas!',
                    position: 'top right'
                });
            });
        },
    },
}
</script>

<style scoped>

    #filter-meeting .form{
        box-shadow: 2px 2px 12px -2px #b5b5b5;
        border-radius: .25rem;
        padding: 10px 30px 20px 30px;
    }
    #filter-meeting .form-row{align-items: flex-end;}
    #filter-meeting .form-group{margin-bottom: 0px !important;}

    .box-checkboxs label{
        font-family: rubik,Sans-serif;
        cursor: pointer;
        margin-left: 10px;
        font-size: 14px;
    }

</style>
