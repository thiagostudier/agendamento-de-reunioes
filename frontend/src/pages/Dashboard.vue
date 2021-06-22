<template>
    <div id="dashboard">
        <!-- CABEÇALHO -->
        <header-vue></header-vue>
        <!-- FILTRO -->
        <filter-meetings :filter="filter" @update="filterMeetings"></filter-meetings>
        <!-- REUNIÕES -->
        <div class="container mt-4">
            <div v-for="meeting in meetings" :key="meeting.id">
                <meeting :meeting="meeting" :auth="auth"></meeting>
            </div>
            <div v-if="!meetings.length">
                <p>Nenhuma reunião encontrada</p>
            </div>
        </div>
    </div>
</template>

<script>

    import HeaderVue from '@/components/HeaderVue';
    import FilterMeetings from '@/components/FilterMeetings';
    import Meeting from '@/components/Meeting';
    import { api } from '@/services/api.ts';
    
    export default {
        name: 'Dashboard',
        components: {
            HeaderVue,
            FilterMeetings,
            Meeting
        },
        data(){
            return{
                auth: false,
                meetings: [],
                filter: {
                    name: null,
                    email: null,
                    subject: null,
                    date: null,
                    start: null,
                    end: null,
                    futureMeetings: null,
                    filteringNewMeetings: null,
                },
            }
        },
        created(){
            if(localStorage.getItem('filtering')){
                this.filter.name = JSON.parse(localStorage.getItem('filtering')).name ? JSON.parse(localStorage.getItem('filtering')).name : null;
                this.filter.email = JSON.parse(localStorage.getItem('filtering')).email ? JSON.parse(localStorage.getItem('filtering')).email : null;
                this.filter.subject = JSON.parse(localStorage.getItem('filtering')).subject ? JSON.parse(localStorage.getItem('filtering')).subject : null;
                this.filter.date = JSON.parse(localStorage.getItem('filtering')).date ? JSON.parse(localStorage.getItem('filtering')).date : null;
                this.filter.start = JSON.parse(localStorage.getItem('filtering')).start ? JSON.parse(localStorage.getItem('filtering')).start : null;
                this.filter.end = JSON.parse(localStorage.getItem('filtering')).end ? JSON.parse(localStorage.getItem('filtering')).end : null;
                this.filter.futureMeetings = JSON.parse(localStorage.getItem('filtering')).futureMeetings ? JSON.parse(localStorage.getItem('filtering')).futureMeetings : null;
                this.filter.filteringNewMeetings = JSON.parse(localStorage.getItem('filtering')).filteringNewMeetings ? JSON.parse(localStorage.getItem('filtering')).filteringNewMeetings : null;
            }
            this.filterMeetings();
            this.getUser();
        },
        methods: {
            filterMeetings(){
                // MÉTODO AXIOS - PEGAR REUNIÕES
                api.post(`filter`, 
                {name: this.filter.name, email: this.filter.email, subject: this.filter.subject, date: this.filter.date, start: this.filter.start, end: this.filter.end, futureMeetings: this.filter.futureMeetings, filteringNewMeetings: this.filter.filteringNewMeetings})
                .then(response => {
                    this.meetings = response.data;
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
            getUser(){
                var user = JSON.parse(localStorage.getItem('user'));
                api.post(`get-me`, {}, 
                {headers: {"Authorization":"Bearer "+user.token}},)
                .then(response => {
                    console.log(response);
                    if(response){
                        this.auth = true;
                    }else{
                        this.auth = false;
                    }
                })
                .catch(e => {
                })
            }
        },
    }

</script>

<style scoped>

</style>