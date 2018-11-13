<template>
    <tile :position="position" modifiers="overflow">
        <section class="statistics">
            <h1>Vélo</h1>
            <ul>
                <li v-for="station in stations" class="statistic">
                    <span class="statistic__label">{{ station.name.substring(4) }}</span>
                    <span>
                        <span class="statistic__available">{{ station.bikes }}</span>
                    </span>
                </li>
            </ul>
        </section>
    </tile>
</template>

<script>
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
    },
};
</script>
