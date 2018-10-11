<template>
    <tile :position="position" modifiers="overflow">
        <section class="calendar">
            <h1 class="calendar__title">{{ calendarName }}</h1>
            <ul class="calendar__events">
                <li v-for="event in calendarEvents.events" class="calendar__event">
                    <h2 class="calendar__event__title">{{ event.name }}</h2>
                    <ul class="calendar__event__attendees">
                        <li v-for="attendee in event.attendees">{{ attendee.name }}</li>
                    </ul>
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
            attendees: [],
        };
    },

    methods: {
        relativeDate,

        getEventHandlers() {
            return {
                'Calendar.EventsFetched': response => {
                    this.attendees = response.calendarEvents[this.calendarSummary].attendees;
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
