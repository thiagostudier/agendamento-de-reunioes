<template>
  <div>
    <!-- CABEÇALHO -->
    <header-vue></header-vue>
    <!-- TEMPLATE -->
    <site-template>
      <!-- FORMULÁRIO -->
      <form-new-meeting></form-new-meeting>
      <br />
      <hr />
      <br />
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
      </div>
      <br />
      <hr />
      <br />
      <!-- AGENDA -->
      <schedule :meetings="meetings"></schedule>
      <br />
      <br />
    </site-template>
  </div>
</template>

<script>

import SiteTemplate from '@/templates/SiteTemplate';
import HeaderVue from '@/components/HeaderVue';
import FormNewMeeting from '@/components/FormNewMeeting.vue';
import Schedule from '@/components/Schedule.vue';
import FilterMeetings from '@/components/FilterMeetings';
import Meeting from '@/components/Meeting';
import { api } from '@/services/api.ts';

export default {
  name: 'Home',
  components: {
    HeaderVue,
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
