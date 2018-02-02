<template>
    <section v-if="offline" class="internet-connection">
        <div class="internet-connection__alert">
            <span class="internet-connection__icon h-icon"></span>
            <span class="internet-connection__text">Internet connection</span>
        </div>
    </section>
</template>

<script>
import echo from '../mixins/echo';
import { addClassModifiers } from '../helpers';
import moment from 'moment';

export default {
    mixins: [echo],

    data() {
        return {
            offline: false,
            lastHeartBeatReceivedAt: moment(),
        };
    },

    created() {
        setInterval(this.determineConnectionStatus, 1000);
    },

    methods: {
        addClassModifiers,

        determineConnectionStatus() {
            const lastHeartBeatReceivedSecondsAgo = moment().diff(
                this.lastHeartBeatReceivedAt,
                'seconds'
            );

            this.offline = lastHeartBeatReceivedSecondsAgo > 125;
        },

        getEventHandlers() {
            return {
                'InternetConnection.Heartbeat': () => {
                    this.lastHeartBeatReceivedAt = moment();
                },
            };
        },
    },
};
</script>
