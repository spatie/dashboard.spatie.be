<template>
    <grid :position="grid">
        <section :class="addClassModifiers('internet-connection', online ? 'up': 'down')">
            <div class="internet-connection__icon">
            </div>
        </section>
    </grid>
</template>

<script>
import echo from '../mixins/echo';
import Grid from './Grid';
import { addClassModifiers } from '../helpers';
import moment from 'moment';

export default {

    components: {
        Grid,
    },

    mixins: [echo],

    props: ['grid'],

    data() {
        return {
            online: true,
            lastHeartBeatReceivedAt: moment(),
        };
    },

    created() {
        setInterval(this.determineConnectionStatus, 1000);
    },

    methods: {
        addClassModifiers,

        determineConnectionStatus() {
            const lastHeartBeatReceivedSecondsAgo = moment().diff(this.lastHeartBeatReceivedAt, 'seconds');

            this.online = lastHeartBeatReceivedSecondsAgo < 125;
        },

        getEventHandlers() {
            return {
                'InternetConnectionStatus.Heartbeat': () => {
                    this.lastHeartBeatReceivedAt = moment();
                },
            };
        },
    },
};
</script>
