<template>
    <div>
        {{ relativeDateTime }}
    </div>
</template>

<script>
import { relativeDateTime } from '../../helpers';

export default {
    props: ['moment'],

    data() {
        return {
            relativeDateTime: '',
        };
    },

    created() {
        this.refreshRelativeDateTime();
        setInterval(this.refreshRelativeDateTime, 1000);
    },

    methods: {
        refreshRelativeDateTime() {
            this.relativeDateTime = relativeDateTime(this.moment);
        },
    },
};
</script>
