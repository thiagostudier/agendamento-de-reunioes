<template>
    <div>
        <h1 class="title">Agenda</h1>
        <full-calendar :options="calendarOptions" :events="events"></full-calendar>
    </div>
</template>

<script>

import 'fullcalendar/dist/fullcalendar.css'
import { FullCalendar } from 'vue-full-calendar'
import { api } from '@/services/api.ts';
import store from '@/store';

export default {
    name: 'Schedule',
    components:{
        FullCalendar
    },
    data(){
        return{
            auth: store.state.user || false,
            events: [],
            calendarOptions: {
                editable: false,
            }
        }
    },
    props: ['meetings'],
    methods:{
        getEvents(){
            this.events = [];
            for (let index = 0; index < this.meetings.length; index++) {
                var title = 'Reunião';
                // SE ESTIVER LOGADO
                if(this.auth && this.auth.token){
                    title = this.meetings[index].name+' | '+this.meetings[index].subject;
                }
                this.events.push({
                    'title': title,
                    'start': this.formatStringDate(this.meetings[index].date)+'T'+this.meetings[index].start,
                    'end': this.formatStringDate(this.meetings[index].date)+'T'+this.meetings[index].end,
                });
            }
        },
        formatStringDate(data) {
            var dia  = data.split("/")[0];
            var mes  = data.split("/")[1];
            var ano  = data.split("/")[2];
            return ano + '-' + ("0"+mes).slice(-2) + '-' + ("0"+dia).slice(-2);
        }
    },
    watch: { 
      	meetings: function(newVal, oldVal) {
            this.getEvents();
        }
    }
}
</script>

<style scoped>



</style>