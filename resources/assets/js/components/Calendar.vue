<template>
    <grid :position="grid" modifiers="overflow padded blue">
        <section class="calendar">
            <h1 class="calendar__title">Upcoming</h1>
            <ul class="calendar__events">
                <li v-for="event in events" class="calendar__event">
                    <h2 class="calendar__event__title">{{ event.name }}</h2>
                    <div class="calendar__event__date">{{ relativeDate(event.date) }}</div>
                </li>
            </ul>
        </section>
    </grid>
</template>

<script>
    import echo from '../mixins/echo';
    import Grid from './atoms/Grid';
    import saveState from 'vue-save-state';
    import { relativeDate } from '../helpers';

    export default {

        components: {
            Grid,
        },

        mixins: [echo, saveState],

        props: ['grid'],

        data() {
            return {
                events: [],
            };
        },

        methods: {
            relativeDate,

            getEventHandlers() {
                return {
                    'Calendar.EventsFetched': response => {
                        this.events = response.events;
                    },
                };
            },

            getSaveStateConfig() {
                return {
                    cacheKey: 'calendar',
                };
            },
        },
    };

</script>
