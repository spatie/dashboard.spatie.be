<template>
    <div>
        {{ relativeDateTime }}
    </div>
</template>

<script>
    import { relativeDateTime } from '../helpers';
    import moment from 'moment';

    export default{

        props: ['moment'],

        data(){
            return{
                relativeDateTime : ''
            }
        },

        created() {
            this.refreshRelativeDateTime
            setInterval(this.refreshRelativeDateTime, 1000);
        },

        methods: {
            refreshRelativeDateTime() {
                console.table([`${moment().diff(this.moment, 'seconds')} seconds ago`,  relativeDateTime(this.moment)]);
                this.relativeDateTime = relativeDateTime(this.moment)
            },
        },
    }

</script>
