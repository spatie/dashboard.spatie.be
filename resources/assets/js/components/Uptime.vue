<template>
    <tile :position="position" :modifiers="hasNotifications? 'overflow yellow' : 'overflow'">
        <section class="uptime">
            <div v-if="hasNotifications">
                <h1 class="uptime__title">Downtime</h1>
                <ul class="uptime__notifications">
                    <li v-for="failing in failingUrls" class="uptime__notification">
                        <h2 class="uptime__notification__title h-ellipsis">{{ failing.url }}</h2>
                        <div class="uptime__notification__time">
                            {{ failing.startedFailingAt | formatDuration }}
                        </div>
                    </li>
                </ul>
            </div>
            <h1 v-else class="uptime__title--up">Sites are up</h1>
        </section>
        <div v-if="!hasNotifications" class="uptime__background h-background-icon"></div>
    </tile>
</template>

<script>
    import echo from '../mixins/echo';
    import Tile from './atoms/Tile';
    import { addClassModifiers, formatDuration } from '../helpers';

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
            hasNotifications() {
                return this.failingUrls.length > 0;
            },
        },


        methods: {
            addClassModifiers,

            getEventHandlers() {
                return {
                    'Uptime.UptimeCheckFailed': response => {
                        this.add(response.url, response.startedFailingAt);
                    },
                    'Uptime.UptimeCheckRecovered': response => {
                        this.remove(response.url);
                    },
                    'Uptime.UptimeCheckSucceeded': response => {
                        this.remove(response.url);
                    },
                };
            },

            add(url, startedFailingAt) {
                this.failingUrls = this.failingUrls.filter(failingUrl => url !== failingUrl.url);

                this.failingUrls.push({ url, startedFailingAt });
            },

            remove(url) {
                this.failingUrls = this.failingUrls.filter(failingUrl => url !== failingUrl.url);
            },
        },
    };
</script>
