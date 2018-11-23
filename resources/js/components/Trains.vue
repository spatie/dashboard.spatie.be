<template>
    <tile :position="position">
        <div class="grid gap-padding h-full markup" style="grid-template-rows: auto 1fr;">
            <div class="flex">
                <div class="grid place-center w-10 h-10 rounded-full" style="background-color: rgba(255, 255, 255, .9)">
                    <div class="text-3xl leading-none -mt-1" v-html="emoji('🚃')" />
                </div>
                <h1 class="ml-2">{{ trains.name }}</h1>
            </div>
            <div class="align-self-center grid gap-8" style="grid-auto-rows: auto;">
                <div>
                    <h2 class="uppercase">Antwerpen</h2>
                    <ul class="mt-padding">
                        <li
                            v-for="train in trains.slice(0,3)"
                            :class="{
                                'line-through' : train.canceled,
                                'text-danger' : train.canceled,
                            }"
                        >
                            <span class="mr-2">
                                {{ train.station }}
                            </span>
                            <span
                                v-if="!train.canceled && train.delay > 0"
                                class="ml-auto font-bold"
                                :class="{ 'text-danger' : train.delay > 0 }"
                            >
                                +{{ train.delay }}m
                            </span>
                            <span class="flex-none w-12 font-bold text-right">
                                {{ formatTime(train.time) }}
                            </span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h2 class="uppercase">Gent</h2>
                    <ul class="mt-padding">
                        <li
                            v-for="train in trains.slice(0,3)"
                            :class="{
                                'line-through' : train.canceled,
                                'text-danger' : train.canceled,
                            }"
                        >
                            <span class="mr-2">
                                {{ train.station }}
                            </span>
                            <span
                                v-if="!train.canceled && train.delay > 0"
                                class="ml-auto font-bold"
                                :class="{ 'text-danger' : train.delay > 0 }"
                            >
                                +{{ train.delay }}m
                            </span>
                            <span class="flex-none w-12 font-bold text-right">
                                {{ formatTime(train.time) }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
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
