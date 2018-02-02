<template>
    <tile :position="position" modifiers="overflow">
        <section class="statistics">
            <h1>Packagist</h1>
            <ul>
                <li class="statistic">
                    <span class="statistic__label">24 hours</span>
                    <span class="statistic__count">{{ formatNumber(daily) }}</span>
                </li>
                <li class="statistic">
                    <span class="statistic__label">30 days</span>
                    <span class="statistic__count">{{ formatNumber(monthly) }}</span>
                </li>
                <li class="statistic">
                    <span class="statistic__label">Total</span>
                    <span class="statistic__count">{{ formatNumber(total) }}</span>
                </li>
            </ul>
        </section>
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
                'Packagist.TotalsFetched': response => {
                    this.daily = response.daily;
                    this.monthly = response.monthly;
                    this.total = response.total;
                },
            };
        },

        getSaveStateConfig() {
            return {
                cacheKey: 'packagist',
            };
        },
    },
};
</script>
