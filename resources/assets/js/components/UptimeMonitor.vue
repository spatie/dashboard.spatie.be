<template>
    <grid :position="grid">
        <section v-if="failingUrls.length === 0">
            <div class="internet-connection__icon">
            </div>
        </section>

        <section v-if="failingUrls.length">
            <ul>
                <li v-for="failing in failingUrls">
                    <h2>{{ failing.url }}</h2>
                    <div>{{ failing.startedFailingAt | formatDuration }}</div>
                </li>
            </ul>
        </section>
    </grid>
</template>

<script>
    import echo from '../mixins/echo';
    import Grid from './Grid';
    import { formatDuration } from '../helpers';

    export default {

        components: {
            Grid,
        },

        filters: {
            formatDuration
        },

        mixins: [echo],

        props: ['grid'],

        data() {
            return {
                failingUrls: [],
            };
        },

        methods: {
            getEventHandlers() {
                return {
                    'UptimeMonitor.UptimeCheckSucceeded': response => {
                        this.remove(response.url);
                    },
                    'UptimeMonitor.UptimeCheckRecovered': response => {
                        this.remove(response.url);
                    },
                    'UptimeMonitor.UptimeCheckFailed': response => {
                        this.add(response.url, response.startedFailingAt);
                    },
                };
            },

            remove(url) {
                this.failingUrls = this.failingUrls.filter(failingUrl => url != failingUrl.url);
            },

            add(url, startedFailingAt) {
                this.failingUrls = this.failingUrls.filter(failingUrl => url != failingUrl.url);
                this.failingUrls.push({ url, startedFailingAt });
            },
        },
    };
</script>
