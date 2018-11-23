<template>
    <div v-if="visibility" style="display:contents"><slot></slot></div>
</template>

<script>
import moment from 'moment-timezone';

export default {
    props: {
        on: {
            type: String,
        },
        off: {
            type: String,
        },
        timeFormat: {
            type: String,
            default: 'HH:mm',
        },
    },

    data() {
        return {
            visibility: false,
        };
    },

    created() {
        this.checkStatus();
        setInterval(this.checkStatus, 10000);
    },

    methods: {
        checkStatus() {
            const beforeTime = moment(this.on, this.timeFormat);
            const afterTime = moment(this.off, this.timeFormat);
            this.visibility = moment().isBetween(beforeTime, afterTime);
        },
    },
};
</script>
