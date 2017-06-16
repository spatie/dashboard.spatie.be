<template>
    <grid-area :position="position" modifiers="overflow">
        <section class="statistics">
            <h1>Package Downloads</h1>
            <ul>
                <li class="statistic">
                    <h2 class="statistic__label">24 hours</h2>
                    <span class="statistic__count">{{ formatNumber(daily) }}</span>
                </li>
                <li class="statistic">
                    <h2 class="statistic__label">30 days</h2>
                    <span class="statistic__count">{{ formatNumber(monthly) }}</span>
                </li>
                <li class="statistic">
                    <h2 class="statistic__label">Total</h2>
                    <span class="statistic__count">{{ formatNumber(total) }}</span>
                </li>
            </ul>
        </section>
    </grid-area>
</template>

<script>
    import { formatNumber } from '../helpers';
    import echo from '../mixins/echo';
    import GridArea from './atoms/GridArea';
    import saveState from 'vue-save-state';

    export default {

        components: {
            GridArea,
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
