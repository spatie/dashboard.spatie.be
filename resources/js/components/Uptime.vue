<template>
    <tile v-if="hasFailingUrls" :position="position" modifiers="overflow yellow above">
        <section class="uptime">
            <h1 class="uptime__title">Downtime</h1>
            <ul class="uptime__notifications">
                <li v-for="failing in failingUrls" class="uptime__notification">
                    <h2 class="uptime__notification__title h-ellipsis">{{ failing.url }}</h2>
                </li>
            </ul>
        </section>
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
        hasFailingUrls() {
            return this.failingUrls.length > 0;
        },
    },

    methods: {
        addClassModifiers,

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
