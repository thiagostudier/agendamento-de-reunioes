<template>
    <div>
        <h1 class="title">Agenda</h1>

        <full-calendar :options="calendarOptions" :events="meetings"></full-calendar>
    </div>
</template>

<script>

import 'fullcalendar/dist/fullcalendar.css'
import { FullCalendar } from 'vue-full-calendar'

export default {
    name: 'Schedule',
    components:{
        FullCalendar
    },
    data(){
        return{
            calendarOptions: {
                // plugins: [ dayGridPlugin, interactionPlugin ],
                initialView: 'dayGridMonth',
                weekends: false // initial value
            }
        }
    },
    props: ['meetings'],
    methods: {
    
    }
}
</script>

<style scoped>



</style>