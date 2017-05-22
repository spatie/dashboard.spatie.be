<template>
    <grid :position="grid" modifiers="padded overflow">
        <section class="packagist-statistics">
            <h1>Package Downloads</h1>
            <ul>
                <li class="packagist-statistic">
                    <h2 class="packagist-statistics__period">24 hours</h2>
                    <span class="packagist-statistics__count">{{ formatNumber(daily) }}</span>
                </li>
                <li class="packagist-statistic">
                    <h2 class="packagist-statistics__period">30 days</h2>
                    <span class="packagist-statistics__count">{{ formatNumber(monthly) }}</span>
                </li>
                <li class="packagist-statistic -total">
                    <h2 class="packagist-statistics__period">Total</h2>
                    <span class="packagist-statistics__count">{{ formatNumber(total) }}</span>
                </li>
            </ul>
        </section>
    </grid>
</template>

<script>
import { formatNumber } from '../helpers';
import echo from '../mixins/echo';
import Grid from './Grid';
import saveState from 'vue-save-state';

export default {

    components: {
        Grid,
    },

    mixins: [echo, saveState],

    props: ['grid'],

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
                cacheKey: 'packagist-statistics',
            };
        },
    },
};

</script>
