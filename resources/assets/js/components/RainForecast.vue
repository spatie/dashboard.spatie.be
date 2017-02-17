<template>
    <grid :position="grid" modifiers="padded">
        <section :class="addClassModifiers('rain-forecast', status)">
            <h1 class="rain-forecast__title" v-if="status === 'rainy'">30' Forecast</h1>
            <h1 class="rain-forecast__title" v-if="status === 'wet'">Stay inside</h1>
            <h1 class="rain-forecast__title" v-if="status === 'dry'">Bikers stay dry</h1>
            <div class="rain-forecast__background"></div>
            <div v-if="status !== 'dry'" class="rain-forecast__graph" >
                <graph
                  :labels="graphLabels"
                  :values="graphData"
                  line-color="rgba(19,134,158, .75)"
                  background-color="rgba(19,134,158, .25)"
                ></graph>
            </div>
        </section>
    </grid>
</template>

<script>
import { filter, map, sumBy } from 'lodash';
import echo from '../mixins/echo';
import Graph from './Graph';
import Grid from './Grid';
import { addClassModifiers } from '../helpers';

export default {

    components: {
        Grid,
        Graph,
    },

    mixins: [echo],

    props: ['grid'],

    computed: {
        status() {

            if (this.noRainPredicted === true) {
                return 'dry';
            }

            if (this.nothingButRainPredicted === true) {
                return 'wet';
            }

            return 'rainy';
        },

        noRainPredicted() {
            const rainScore = sumBy(this.forecast, foreCastItem => {
                return parseInt(foreCastItem.chanceOfRain);
            });

            return rainScore === 0;
        },

        nothingButRainPredicted() {
            const foreCastItemWithNoRain = filter(this.forecast, foreCastItem => {
                return foreCastItem.chanceOfRain < 40;
            }).length;

            return foreCastItemWithNoRain.length === 0;
        },

        graphLabels() {
            return map(this.forecast, 'minutes');
        },

        graphData() {
            return map(this.forecast, 'chanceOfRain');
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
        addClassModifiers,

        getEventHandlers() {
            return {
                'RainForecast.ForecastFetched': response => {
                    this.forecast = response.forecast;
                },
            };
        },
    },
};
</script>
