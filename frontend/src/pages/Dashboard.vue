<template>
    <div id="dashboard">
        <!-- CABEÇALHO -->
        <header-vue></header-vue>
        <!-- FILTRO -->
      <filter-meetings :updateMeetings="updateMeetings"></filter-meetings>
        <!-- REUNIÕES -->
        <div class="container mt-4">
            <div v-for="meeting in meetings" :key="meeting.id">
                <meeting :meeting="meeting" :auth="auth"></meeting>
            </div>
            <div v-if="!meetings.length">
                <p>Nenhuma reunião encontrada</p>
            </div>
            <br />
            <hr />
            <br />
            <!-- AGENDA -->
            <schedule :meetings="meetings"></schedule>
        </div>
    </div>
</template>

<script>

    import HeaderVue from '@/components/HeaderVue';
    import FilterMeetings from '@/components/FilterMeetings';
    import Meeting from '@/components/Meeting';
    import Schedule from '@/components/Schedule.vue';
    import { api } from '@/services/api.ts';

    export default {
        name: 'Dashboard',
        components: {
            HeaderVue,
            FilterMeetings,
            Meeting,
            Schedule
        },
        data(){
            return{
                auth: JSON.parse(localStorage.getItem('auth')),
                meetings: [],
            }
        },
        methods:{
            updateMeetings(data){
                this.meetings = data;
            }
        }
    }

</script>

<style scoped>

</style>