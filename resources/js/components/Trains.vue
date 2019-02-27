<template>
    <tile :position="position">
        <div class="grid gap-padding h-full markup" style="grid-template-rows: auto 1fr;">
            <div class="flex">
                <div class="grid place-center w-10 h-10 rounded-full" style="background-color: rgba(255, 255, 255, .9)">
                    <div class="text-3xl leading-none -mt-1" v-html="emoji('🚃')" />
                </div>
                <h1 class="ml-2">{{ trainConnections.name }}</h1>
            </div>
            <div class="align-self-center grid gap-8" style="grid-auto-rows: auto;">
                <div v-for="trainConnection in trainConnections">
                    <h2 class="uppercase">{{ trainConnection.label }}</h2>
                    <ul class="mt-padding">
                        <li
                            v-for="train in trainConnection.trains.slice(0, maxTrains)"
                            :class="{
                                'line-through': train.canceled,
                                'text-danger': train.canceled,
                            }"
                        >
                            <span class="mr-2" v-html="train.station" />
                            <span
                                v-if="!train.canceled && train.delay > 0"
                                class="ml-auto mr-2 font-bold variant-tabular"
                                :class="{ 'text-danger': train.delay > 0 }"
                                v-html="`+${train.delay}m`"
                            />
                            <span
                                class="flex-none font-bold text-right variant-tabular"
                                v-html="formatTime(train.time)"
                            />
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

    props: {
        position: {
            type: String,
        },
        maxTrains: {
            type: Number,
            default: 5,
        },
    },

    data() {
        return {
            trainConnections: [],
        };
    },

    methods: {
        emoji,
        formatTime,

        getEventHandlers() {
            return {
                'Trains.TrainConnectionsFetched': response => {
                    console.log(response);
                    this.trainConnections = response.trainConnections;
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
