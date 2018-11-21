<template>
    <tile :position="position">
        <div class="grid gap-padding h-full markup" style="grid-template-rows: auto 1fr;">
            <div class="flex">
                <div class="grid place-center w-10 h-10 rounded-full" style="background-color: rgba(255, 255, 255, .9)">
                    <div class="text-3xl leading-none -mt-2" v-html="emoji('🚆')" />
                </div>
                <h1 class="ml-2">{{ liveboard.name }}</h1>
            </div>
            <ul class="align-self-center">
                <li v-for="departure in liveboard.departures">
                    <span :class="{
                        'line-through' : departure.canceled,
                        'text-danger' : departure.canceled,
                    }">
                        {{ departure.station }}
                    </span>
                    <span v-if="!departure.canceled">
                        <span
                                :class="{ 'text-danger' : departure.delay > 0 }"
                                class="font-bold">
                            <span v-if="departure.delay > 0 ">+{{ departure.delay }}</span>&nbsp;{{ formatTime(departure.time) }}
                        </span>
                    </span>
                </li>
            </ul>
        </div>
    </tile>
</template>

<script>
    import { emoji, formatTime } from '../helpers';
    import echo from '../mixins/echo';
    import Tile from './atoms/Tile';
    import saveState from 'vue-save-state';

    export default {
        components: {
            Tile,
        },

        mixins: [echo, saveState],

        props: ['position'],

        data() {
            return {
                liveboard: [],
            };
        },

        methods: {
            emoji,
            formatTime,

            getEventHandlers() {
                return {
                    'Trains.TrainsFetched': response => {
                        this.liveboard = response.liveboard;
                    },
                };
            },

            getSaveStateConfig() {
                return {
                    cacheKey: 'trains',
                };
            },
        },
    };
</script>
