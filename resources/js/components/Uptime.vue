<template>
    <tile v-if="hasFailingUrls" :position="position" class="markup bg-warn">
        <div class="grid gap-padding h-full markup" style="grid-template-rows: auto 1fr">
            <h1>Downtime</h1>
            <ul class="align-self-center">
                <li v-for="failing in failingUrls">
                    <div class="font-bold truncate">{{ failing.url }}</div>
                </li>
            </ul>
        </div>
    </tile>
</template>

<script>
import echo from '../mixins/echo';
import Tile from './atoms/Tile';
import { formatDuration } from '../helpers';

export default {
    components: {
        Tile,
    },

    filters: {
        formatDuration,
    },

    mixins: [echo],

    props: ['position'],

    data() {
        return {
            failingUrls: [],
        };
    },

    computed: {
        hasFailingUrls() {
            return this.failingUrls.length > 0;
        },
    },

    methods: {
        getEventHandlers() {
            return {
                'Uptime.UptimeCheckFailed': response => {
                    this.add(response.url);
                },
                'Uptime.UptimeCheckRecovered': response => {
                    this.remove(response.url);
                },
                'Uptime.UptimeCheckSucceeded': response => {
                    this.remove(response.url);
                },
            };
        },

        add(url) {
            this.failingUrls = this.failingUrls.filter(failingUrl => url !== failingUrl.url);

            this.failingUrls.push({ url });
        },

        remove(url) {
            this.failingUrls = this.failingUrls.filter(failingUrl => url !== failingUrl.url);
        },
    },
};
</script>
