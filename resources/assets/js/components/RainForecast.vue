<template>
    <grid :position="grid" modifiers="padded">
        <section :class="modifyClass(status, 'rain-forecast')">
            <h1 class="rain-forecast__title rain-forecast__title--rainy" v-if="status == 'rainy'">30' FORECAST</h1>
            <h1 class="rain-forecast__title rain-forecast__title--rainy" v-if="status == 'wet'">STAY INSIDE</h1>
            <div class="rain-forecast__background"></div>
            <div class="rain-forecast__graph" v-if="status == 'rainy'">
                <graph
                  :labels="graphLabels"
                  :values="graphData"
                  line-color="rgba(19,134,158, .5)"
                  background-color="rgba(19,134,158, .25)"
                ></graph>
            </div>
        </section>
    </grid>
</template>

<script>
import _ from 'lodash';
import Echo from '../mixins/echo';
import Graph from './graph';
import Grid from './grid';
import { modifyClass } from '../helpers';

export default {

    components: {
        Grid,
        Graph,
    },

    mixins: [Echo],

    props: ['grid'],

    computed: {
        status() {
            if (this.noRainPredicted()) {
                return 'dry';
            }

            if (this.nothingButRainPredicted()) {
                return 'wet';
            }

            return 'rainy';
        },
        graphLabels() {
            return _.map(this.forecast, 'minutes');
        },
        graphData() {
            return _.map(this.forecast, 'chanceOfRain');
        },
        graphPeriod() {
            return this.forecast[this.forecast.length - 1].minutes;
        },
    },

    data() {
        return {
            forecast: [],
        };
    },

    methods: {
        modifyClass,
        getEventHandlers() {
            return {
                'RainForecast.ForecastFetched': response => {
                    this.forecast = response.forecast;
                },
            };
        },
        getSavedStateId() {
            return 'rain-forecast-update';
        },
        noRainPredicted() {
            const rainScore = _.sumBy(this.forecast, foreCastItem => {
                return parseInt(foreCastItem.chanceOfRain);
            });

            return rainScore === 0;
        },
        nothingButRainPredicted() {
            const foreCastItemWithNoRain = _.filter(this.forecast, foreCastItem => {
                return foreCastItem.chanceOfRain < 40;
            }).length;

            return foreCastItemWithNoRain.length === 0;
        },
    },
};
</script>
