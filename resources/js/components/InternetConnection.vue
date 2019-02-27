<template>
    <tile :position="position" class="z-10" style="--bg-tile: transparent" no-fade>
        <div v-if="offline" class="flex">
            <div class="px-2 mx-auto font-black text-invers bg-error rounded-full shadow-lg">No connection</div>
        </div>
    </tile>
</template>

<script>
import echo from '../mixins/echo';
import moment from 'moment';
import Tile from './atoms/Tile';

export default {
    components: {
        Tile,
    },

    mixins: [echo],

    props: ['position'],

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
            const lastHeartBeatReceivedSecondsAgo = moment().diff(this.lastHeartBeatReceivedAt, 'seconds');

            this.offline = lastHeartBeatReceivedSecondsAgo > 125;
        },

        getEventHandlers() {
            return {
                'Dashboard.Heartbeat': () => {
                    this.lastHeartBeatReceivedAt = moment();
                },
            };
        },
    },
};
</script>
