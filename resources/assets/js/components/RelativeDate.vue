<template>
    <div>
        {{ relativeDate }}
    </div>
</template>



<script>
    import { relativeDate } from '../helpers';

    export default{

        props: ['moment'],

        data(){
            return{
                relativeDate : ''
            }
        },

        created() {
            setInterval(() => this.relativeDate = relativeDate(this.moment), 1000);
        },
    }
</script>
