<template>
    <site-template>
        <!-- FILTRO -->
        <filter-meetings class="mb-4" :updateMeetings="updateMeetings"></filter-meetings>
        <!-- REUNIÕES -->
        <div class="container mt-4">
            <div v-for="meeting in meetings" :key="meeting.id">
                <meeting :meeting="meeting" :auth="auth"></meeting>
            </div>
            <!-- AGENDA -->
            <schedule :meetings="meetings"></schedule>
        </div>
    </site-template>
</template>

<script>

    import FilterMeetings from '@/components/FilterMeetings';
    import Meeting from '@/components/Meeting';
    import Schedule from '@/components/Schedule';
    import SiteTemplate from '@/templates/SiteTemplate';
    import store from '@/store';

    export default {
        name: 'Dashboard',
        components: {
            SiteTemplate,
            FilterMeetings,
            Meeting,
            Schedule
        },
        data(){
            return{
                auth: store.state.user || false,
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