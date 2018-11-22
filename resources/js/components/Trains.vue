<template>
    <tile :position="position">
        <div class="grid gap-padding h-full markup" style="grid-template-rows: auto 1fr;">
            <div class="flex">
                <div class="grid place-center w-10 h-10 rounded-full" style="background-color: rgba(255, 255, 255, .9)">
                    <div class="text-3xl leading-none -mt-2" v-html="emoji('🚆')" />
                </div>
                <h1 class="ml-2">{{ trains.name }}</h1>
            </div>
            <ul class="align-self-center">
                <li v-for="train in trains">
                    <span :class="{
                        'line-through' : train.canceled,
                        'text-danger' : train.canceled,
                    }">
                        {{ train.station }}
                    </span>
                    <span v-if="!train.canceled">
                        <span
                                :class="{ 'text-danger' : train.delay > 0 }"
                                class="font-bold">
                            <span v-if="train.delay > 0 ">+{{ train.delay }}m</span>&nbsp;{{ formatTime(train.time) }}
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
                trains: [],
            };
        },

        methods: {
            emoji,
            formatTime,

            getEventHandlers() {
                return {
                    'Trains.TrainsFetched': response => {
                        this.trains = response.trains;
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
