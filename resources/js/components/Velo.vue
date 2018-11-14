<template>
    <tile :position="position">
        <div class="grid gap-padding h-full markup" style="--template-rows: auto 1fr;">
            <h1>
                <span class="absolute text-3xl -mt-4" v-html="renderTitleIcon()"></span>
                <span class="opacity-0">Velo</span>
            </h1>
            <ul>
                <li v-for="station in stations">
                    <span :class="station.bikes == 0 ? 'line-through' : ''">{{ station.name.substring(4) }}</span>
                    <span>
                        <span :class="station.bikes < 3 ? 'text-danger' : ''" class="font-medium">{{ station.bikes }}</span>
                    </span>
                </li>
            </ul>
        </div>
    </tile>
</template>

<script>
import twemoji from 'twemoji';
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

        renderTitleIcon() {
            return twemoji.parse('🚲');
        },

    },

};
</script>
