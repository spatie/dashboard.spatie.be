<template>
    <grid :position="grid">
        <section :class="modifyClass(online ? 'up': 'down', 'internet-connection')">
            <div class="internet-connection__icon">
            </div>
        </section>
    </grid>
</template>

<script>
import Echo from '../mixins/echo';
import Grid from './grid';
import { modifyClass } from '../helpers';
import moment from 'moment';

export default {

    components: {
        Grid,
    },

    mixins: [
        Echo
    ],

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
        modifyClass,
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
