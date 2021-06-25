<template>
    <site-template>
        <!-- FORMULÁRIO -->
        <form-new-meeting></form-new-meeting>
    </site-template>
</template>

<script>
    import SiteTemplate from '@/templates/SiteTemplate';
    import FormNewMeeting from '@/components/FormNewMeeting';
    
    export default {
        name: 'UpdateMeeting',
        components: {
            SiteTemplate,
            FormNewMeeting
        },
    }

</script>

<style scoped>

</style>