<template>
    <tile :position="position">
        <div class="grid gap-padding h-full markup" style="grid-template-rows: 1.5rem 1fr;">
            <h1>Upcoming</h1>
            <ul class="grid gap-0 h-full">
                <li v-for="event in events">
                    <div class="my-auto">
                        <div class="font-medium" :class="withinWeek(event.date) ? 'text-accent' : ''">{{ event.name }}</div>
                        <div class="text-sm text-dimmed">{{ relativeDate(event.date) }}</div>
                    </div>
                </li>
            </ul>
        </div>
    </tile>
</template>

<script>
import echo from '../mixins/echo';
import Tile from './atoms/Tile';
import saveState from 'vue-save-state';
import { relativeDate, withinWeek } from '../helpers';

export default {
    components: {
        Tile,
    },

    mixins: [echo, saveState],

    props: ['position'],

    data() {
        return {
            events: [],
        };
    },

    methods: {
        relativeDate,
        withinWeek,

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
