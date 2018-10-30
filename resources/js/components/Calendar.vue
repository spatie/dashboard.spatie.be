<template>
    <tile :position="position">
        <div class="grid h-full" style="--template-rows: auto 1fr; --gap: 0">
            <h1 class="style-title">Upcoming</h1>
            <ul class="mt-2 style-list grid h-full">
                <li v-for="event in events" class="flex-col justify-center">
                    <div class="font-medium" :class="withinWeek(event.date) ? 'text-accent' : ''">{{ event.name }}</div>
                    <div class="text-sm text-dimmed">{{ relativeDate(event.date) }}</div>
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
