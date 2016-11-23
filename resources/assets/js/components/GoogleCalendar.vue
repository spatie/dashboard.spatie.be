<template>
    <grid :position="grid" modifiers="overflow padded blue">
       <section class="google-calendar">
           <h1>Upcoming</h1>
           <ul class="google-calendar__events">
               <li v-for="event in events"  class="google-calendar__event">
                   <h2 class="google-calendar__event__title">{{ event.name }}</h2>
                   <div class="google-calendar__event__date">{{ relativeDate(event.date) }}</div>
               </li>
           </ul>
       </section>
    </grid>
</template>

<script>
import { relativeDate } from '../helpers';
import echo from '../mixins/echo';
import Grid from './Grid';
import saveState from 'vue-save-state';

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
                'GoogleCalendar.EventsFetched': response => {
                    this.events = response.events;
                },
            };
        },

        getSaveStateConfig() {
            return {
                cacheKey: 'google-calendar',
            };
        },
    },
};
</script>
