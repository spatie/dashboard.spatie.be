<template>
    <tile :position="position">
        <div class="grid gap-padding h-full markup" style="grid-template-rows: auto 1fr;">
            <div class="text-3xl -mt-4" v-html="emoji('🚲')" />
            <ul class="align-self-center">
                <li v-for="station in stations">
                    <span :class="{
                        'line-through' : isStationEmpty(station),
                        'text-danger' : isStationNearEmpty(station)
                    }">
                        {{ station.name.substring(4) }}
                    </span>
                    <span>
                        <span
                            :class="{ 'text-danger' : isStationNearEmpty(station) }"
                            class="font-bold">
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
