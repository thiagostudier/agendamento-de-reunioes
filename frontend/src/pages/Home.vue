<template>
  <!-- TEMPLATE -->
  <site-template>
    <!-- FORMULÁRIO -->
    <form-new-meeting class="mb-4"/>
    <!-- FILTRO -->
    <filter-meetings class="mb-4" :updateMeetings="updateMeetings"></filter-meetings>
    <!-- REUNIÕES -->
    <div class="container mb-4">
      <div v-for="meeting in meetings" :key="meeting.id">
        <meeting :meeting="meeting" :auth="auth"></meeting>
      </div>
      <div v-if="!meetings.length">
        <p>Nenhuma reunião encontrada</p>
      </div>
    </div>
    <!-- AGENDA -->
    <schedule :meetings="meetings"></schedule>
  </site-template>
</template>

<script>

import SiteTemplate from '@/templates/SiteTemplate';
import FormNewMeeting from '@/components/FormNewMeeting';
import Schedule from '@/components/Schedule';
import FilterMeetings from '@/components/FilterMeetings';
import Meeting from '@/components/Meeting';

export default {
  name: 'Home',
  components: {
    SiteTemplate,
    FormNewMeeting,
    Schedule,
    FilterMeetings,
    Meeting
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
