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
      <br />
      <hr />
      <br />
      <!-- AGENDA -->
      <schedule></schedule>
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
    this.getMeetings();
  },
  data(){
    return{
      auth: JSON.parse(localStorage.getItem('auth')),
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
        filteringMeetingsAccept: null,
      },
    }
  },
  created(){
    // PEGAR DADOS DOS FILTROS EM LOCALHOST
    if(localStorage.getItem('filtering')){
      this.filter.name = JSON.parse(localStorage.getItem('filtering')).name ? JSON.parse(localStorage.getItem('filtering')).name : null;
      this.filter.email = JSON.parse(localStorage.getItem('filtering')).email ? JSON.parse(localStorage.getItem('filtering')).email : null;
      this.filter.subject = JSON.parse(localStorage.getItem('filtering')).subject ? JSON.parse(localStorage.getItem('filtering')).subject : null;
      this.filter.date = JSON.parse(localStorage.getItem('filtering')).date ? JSON.parse(localStorage.getItem('filtering')).date : null;
      this.filter.start = JSON.parse(localStorage.getItem('filtering')).start ? JSON.parse(localStorage.getItem('filtering')).start : null;
      this.filter.end = JSON.parse(localStorage.getItem('filtering')).end ? JSON.parse(localStorage.getItem('filtering')).end : null;
      this.filter.futureMeetings = JSON.parse(localStorage.getItem('filtering')).futureMeetings ? JSON.parse(localStorage.getItem('filtering')).futureMeetings : null;
      this.filter.filteringNewMeetings = JSON.parse(localStorage.getItem('filtering')).filteringNewMeetings ? JSON.parse(localStorage.getItem('filtering')).filteringNewMeetings : null;
      this.filter.filteringMeetingsAccept = JSON.parse(localStorage.getItem('filtering')).filteringMeetingsAccept ? JSON.parse(localStorage.getItem('filtering')).filteringMeetingsAccept : null;
    }
    // FILTRAR DADOS
    this.filterMeetings();
  },
  methods: {
    filterMeetings(){
      // MÉTODO AXIOS - PEGAR REUNIÕES
      api.post(`filter`, 
      {name: this.filter.name, email: this.filter.email, subject: this.filter.subject, date: this.filter.date, start: this.filter.start, end: this.filter.end, futureMeetings: this.filter.futureMeetings, filteringNewMeetings: this.filter.filteringNewMeetings, filteringMeetingsAccept: this.filter.filteringMeetingsAccept})
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
  }
}
</script>

<style scoped>
</style>
