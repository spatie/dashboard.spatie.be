<template>
    <grid :position="grid">
        <section v-if="offlineUrls.length === 0">
            <div class="internet-connection__icon">
            </div>
        </section>

        <section v-if="offlineUrls.length">
            <ul>
                <li v-for="offline in offlineUrls">
                    <h2>{{ offline.url }}</h2>
                    <div>{{ offline.startedFailingAt | formatDuration }}</div>
                </li>
            </ul>
        </section>
    </grid>
</template>

<script>
    import echo from '../mixins/echo';
    import Grid from './Grid';
    import moment from 'moment';
    import duration from 'moment-duration-format';

    export default {

        components: {
            Grid,
        },

        filters: {
            formatDuration(start) {
                return moment.duration(moment().diff(start), 'milliseconds').format("d[d] h[h] m[m]");
            }
        },

        mixins: [echo],

        props: ['grid'],

        data() {
            return {
                offlineUrls: [],
            };
        },

        methods: {
            getEventHandlers() {
                return {
                    'UptimeMonitor.UptimeCheckSucceeded': response => {
                        this.online(response.url);
                    },
                    'UptimeMonitor.UptimeCheckRecovered': response => {
                        this.online(response.url);
                    },
                    'UptimeMonitor.UptimeCheckFailed': response => {
                        this.offline(response.url, response.startedFailingAt);
                    },
                };
            },

            online(url) {
                this.offlineUrls = this.offlineUrls.filter(offlineUrl => url != offlineUrl.url);
            },

            offline(url, startedFailingAt) {
                this.offlineUrls = this.offlineUrls.filter(offlineUrl => url != offlineUrl.url);
                this.offlineUrls.push({ url, startedFailingAt });
            },
        },
    };
</script>
