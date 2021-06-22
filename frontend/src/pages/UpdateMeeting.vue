<template>
    <div id="dashboard">
        <!-- CABEÇALHO -->
        <header-vue></header-vue>
        <!-- FORMULÁRIO -->
        <div class="container">
            <form-new-meeting></form-new-meeting>
        </div>
    </div>
</template>

<script>

    import HeaderVue from '@/components/HeaderVue';
    import FormNewMeeting from '@/components/FormNewMeeting.vue';
    
    export default {
        name: 'UpdateMeeting',
        components: {
            HeaderVue,
            FormNewMeeting
        },
    }

</script>

<style scoped>

</style>