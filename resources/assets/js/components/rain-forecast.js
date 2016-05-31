import _ from 'lodash';
import Graph from './graph';
import Grid from './grid';
import moment from 'moment';
import Pusher from '../mixins/pusher';
import SaveState from '../mixins/save-state';

export default {

    template: `
        <grid :position="grid" modifiers="padded">
            <section :class="status | modify-class 'rain-forecast'">
                <div class="rain-forecast__icon" v-if="status == 'dry' || status == 'wet' ">
                </div>
                <h1 class="rain-forecast__title rain-forecast__title--rainy" v-if="status == 'rainy'">30"</h1>
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
    `,

    components: {
        Grid, Graph,
    },

    mixins: [Pusher, SaveState],

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
            return this.forecast[this.forecast.length-1].minutes;
        },
    },

    data() {
        return {
            forecast: [
            ],
        };
    },

    methods: {
        getEventHandlers() {
            return {
                'App\\Components\\RainForecast\\Events\\ForecastFetched': response => {
                    this.forecast = response.forecast;
                },
            };
        },

        getSavedStateId() {
            return 'rain-forecast';
        },

        noRainPredicted() {
            let rainScore = _.sumBy(this.forecast, foreCastItem => {
                return parseInt(foreCastItem.chanceOfRain);
            });

            return rainScore == 0;
        },

        nothingButRainPredicted() {
            let foreCastItemWithNoRain = _.filter(this.forecast, foreCastItem => {
                return foreCastItem.chanceOfRain < 40;
            }).length;

            return foreCastItemWithNoRain.length == 0;
        },
    },
};
