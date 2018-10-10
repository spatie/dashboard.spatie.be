<template>
    <tile :position="position" >
        <h1 class=tile-title>NPM</h1>
        <ul class="tile-stats">
            <li>
                <span>24 hours</span>
                <span>{{ formatNumber(daily) }}</span>
            </li>
            <li>
                <span>30 days</span>
                <span>{{ formatNumber(monthly) }}</span>
            </li>
            <li>
                <span>Total</span>
                <span>{{ formatNumber(total) }}</span>
            </li>
        </ul>
    </tile>
</template>

<script>
import { formatNumber } from '../helpers';
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
            daily: 0,
            monthly: 0,
            total: 0,
        };
    },

    methods: {
        formatNumber,

        getEventHandlers() {
            return {
                'Npm.TotalsFetched': response => {
                    this.daily = response.daily;
                    this.monthly = response.monthly;
                    this.total = response.total;
                },
            };
        },

        getSaveStateConfig() {
            return {
                cacheKey: 'npm',
            };
        },
    },
};
</script>
