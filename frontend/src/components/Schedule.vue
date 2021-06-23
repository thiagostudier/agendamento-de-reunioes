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

export default {
    name: 'Schedule',
    components:{
        FullCalendar
    },
    data(){
        return{
            auth: JSON.parse(localStorage.getItem('auth')),
            schedule_meetings: [],
            events: [],
            calendarOptions: {
                editable: false,
            }
        }
    },
    created(){
        this.getMeetingsSchedule();
    },
    methods:{
        getMeetingsSchedule(){
            // MÉTODO AXIOS - PEGAR REUNIÕES
            api.post(`filter`, 
            {filteringMeetingsAccept: true})
            .then(response => {
                this.schedule_meetings = response.data;
                this.getEvents();
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
        getEvents(){
            for (let index = 0; index < this.schedule_meetings.length; index++) {
                var title = 'Reunião';
                // SE ESTIVER LOGADO
                if(this.auth && this.auth.token){
                    title = this.schedule_meetings[index].name+' | '+this.schedule_meetings[index].subject;
                }
                this.events.push({
                    'title': title,
                    'start': this.formatStringDate(this.schedule_meetings[index].date)+'T'+this.schedule_meetings[index].start,
                    'end': this.formatStringDate(this.schedule_meetings[index].date)+'T'+this.schedule_meetings[index].end,
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
}
</script>

<style scoped>



</style>