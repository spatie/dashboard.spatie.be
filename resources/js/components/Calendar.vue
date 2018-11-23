<template>
    <tile :position="position">
        <div class="grid gap-padding h-full markup">
            <ul class="align-self-center">
                <li v-for="event in events">
                    <div class="my-2">
                        <div :class="{ 'font-bold': withinWeek(event.date) }">{{ event.name }}</div>
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
