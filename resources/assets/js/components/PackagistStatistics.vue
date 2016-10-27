<template>
    <grid :position="grid" modifiers="padded overflow">
        <section class="packagist-statistics">
            <h1>Package Downloads</h1>
                <ul>
                <li class="packagist-statistic">
                    <span class="packagist-statistics__stars"></span>
                    <span class="packagist-statistics__count">{{ formatNumber(stars) }}</span>
                </li>
                <li class="packagist-statistic">
                    <h2 class="packagist-statistics__period">Today</h2>
                    <span class="packagist-statistics__count">{{ formatNumber(daily) }}</span>
                </li>
                <li class="packagist-statistic">
                    <h2 class="packagist-statistics__period">This month</h2>
                    <span class="packagist-statistics__count">{{ formatNumber(monthly) }}</span>
                </li>
                <li class="packagist-statistic -total">
                    <h2 class="packagist-statistics__period">Total Downloads</h2>
                    <span class="packagist-statistics__count">{{ formatNumber(total) }}</span>
                </li>
            </ul>
        </section>
    </grid>
</template>

<script>
import { formatNumber } from '../helpers';
import Echo from '../mixins/echo';
import Grid from './grid';
import SaveState from '../mixins/save-state';

export default {

    components: {
        Grid,
    },

    mixins: [Echo, SaveState],

    props: ['grid'],

    data() {
        return {
            stars: 0,
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
                    this.stars = response.stars;
                    this.daily = response.daily;
                    this.monthly = response.monthly;
                    this.total = response.total;
                },
            };
        },

        getSavedStateId() {
            return 'packagist-statistics';
        },
    },
};
</script>
