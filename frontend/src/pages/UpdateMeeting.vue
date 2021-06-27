<template>
    <site-template>
        <!-- FORMULÁRIO -->
        <form-new-meeting :id="id"></form-new-meeting>
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
        props: ['id']
    }

</script>

<style scoped>

</style>