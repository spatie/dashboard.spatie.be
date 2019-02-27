<template>
    <tile :position="position">
        <div class="grid gap-padding h-full markup" style="grid-template-rows: auto 1fr;">
            <div class="grid place-center w-10 h-10 rounded-full" style="background-color: rgba(255, 255, 255, .9)">
                <div class="text-3xl leading-none -mt-1" v-html="emoji('🚲')" />
            </div>
            <ul class="align-self-center">
                <li v-for="station in stations">
                    <span
                        :class="{
                            'line-through': isStationEmpty(station),
                            'text-danger': isStationNearEmpty(station),
                        }"
                    >
                        {{ station.name.substring(4) }}
                    </span>
                    <span>
                        <span :class="{ 'text-danger': isStationNearEmpty(station) }" class="font-bold variant-tabular">
                            {{ station.bikes }}
                        </span>
                    </span>
                </li>
            </ul>
        </div>
    </tile>
</template>

<script>
import { emoji } from '../helpers';
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
            stations: [],
        };
    },

    methods: {
        emoji,

        isStationEmpty(station) {
            return station.bikes == 0;
        },

        isStationNearEmpty(station) {
            return station.bikes < 3;
        },

        getEventHandlers() {
            return {
                'Velo.StationsFetched': response => {
                    this.stations = response.stations;
                },
            };
        },

        getSaveStateConfig() {
            return {
                cacheKey: 'velo',
            };
        },
    },
};
</script>
