<template>
    <tile :position="position" modifiers="overflow">
        <section class="calendar">
            <h1 class="calendar__title">{{ calendarName }}</h1>
            <ul class="calendar__events">
                <li v-for="event in calendarEvents.events" class="calendar__event">
                    <h2 class="calendar__event__title">{{ event.name }}</h2>
                    <textarea class="calendar__event__date">{{ event.description }}</textarea>
                    <!--
                    <div class="calendar__event__date">{{ event.description }}</div>
                    <div class="calendar__event__date">{{ relativeDate(event.date) }}</div>
                    -->
                </li>
            </ul>
        </section>
    </tile>
</template>

<script>
import echo from '../mixins/echo';
import Tile from './atoms/Tile';
import saveState from 'vue-save-state';
import { relativeDate } from '../helpers';

export default {
    components: {
        Tile,
    },

    mixins: [echo, saveState],

    props: ['position', 'calendarSummary'],

    data() {
        return {
            calendarName: "",
            calendarEvents: [],
        };
    },

    methods: {
        relativeDate,

        getEventHandlers() {
            return {
                'Calendar.EventsFetched': response => {
                    console.log(response.calendarEvents[this.calendarSummary]);
                    this.calendarName = response.calendarEvents[this.calendarSummary].calendarName;
                    this.calendarEvents = response.calendarEvents[this.calendarSummary];
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
