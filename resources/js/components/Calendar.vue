<template>
    <tile :position="position">
        <div class="grid h-full" style="--template-rows: auto 1fr; --gap: 1rem">
            <h1 class="tile-title">Upcoming</h1>
            <ul class="tile-list grid h-full">
                <li v-for="event in events" class="flex flex-col justify-center">
                    <div class="font-bold">{{ event.name }}</div>
                    <div class="text-sm">{{ relativeDate(event.date) }}</div>
                </li>
            </ul>
        </div>
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

    props: ['position'],

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
