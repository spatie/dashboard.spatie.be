<template>
    <div v-if="offline" class="fixed pin">
        <div class="fixed pin bg-tile opacity-25"></div>
        <div class="grid justify-items-center fixed pin-b pin-l pin-r">
            <span class="px-4 py-2 mb-8 font-black text-invers bg-error rounded-full shadow-lg">No connection</span>
        </div>
    </div>
</template>

<script>
import echo from '../mixins/echo';
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
