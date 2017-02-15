<template>
    <grid :position="grid" :modifiers="hasNotifications? 'overflow padded yellow' : 'padded'">
        <section :class="hasNotifications ? 'uptime-monitor' : addClassModifiers('uptime-monitor', 'empty')" >
            <div v-if="hasNotifications">
                <h1 class="uptime-monitor__title">Downtime</h1>
                <ul class="uptime-monitor__notifications">
                    <li v-for="failing in failingUrls" class="uptime-monitor__notification">
                        <h2 class="uptime-monitor__notification__title">{{ failing.url }}</h2>
                        <div class="uptime-monitor__notification__time">
                            {{ failing.startedFailingAt | formatDuration }}
                        </div>
                    </li>
                </ul>
            </div>

            <h1 v-if="!hasNotifications" class="uptime-monitor__title">Sites are up</h1>
            <div v-if="!hasNotifications" class="uptime-monitor__background"></div>
        </section>
    </grid>
</template>

<script>
    import echo from '../mixins/echo';
    import Grid from './Grid';
    import { addClassModifiers, formatDuration } from '../helpers';

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

        computed: {
            hasNotifications() {
                return this.failingUrls.length > 0;
            }
        },


        methods: {

            addClassModifiers,

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
