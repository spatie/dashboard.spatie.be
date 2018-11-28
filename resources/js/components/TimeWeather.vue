<template>
    <tile :position="position">
        <div
            class="grid gap-2 justify-items-center h-full"
            style="grid-template-rows: auto 1fr auto;"
        >
            <div class="markup">
                <h1>{{ date }}</h1>
            </div>
            <div class="align-self-center font-bold text-4xl tracking-wide leading-none">
                {{ time }}
            </div>
            <div class="uppercase">
                <div
                    class="grid gap-4 items-center"
                    style="grid-template-columns: repeat(3, auto);"
                >
                    <span>
                        {{ weather.temperature }}°
                        <span class="text-sm uppercase text-dimmed">out</span>
                    </span>
                    <span>
                        <office-temperature />
                        <span class="text-sm uppercase text-dimmed">in</span>
                    </span>
                    <span class="text-2xl" v-html="weather.icon"></span>
                </div>
                <div class="hidden">{{ weatherCity }}</div>
            </div>
        </div>
        <div
            class="absolute pin-b pin-l w-full grid items-end"
            style="
                height: calc(1.25 * var(--tile-padding));
                grid-gap: 1px;
                grid-template-columns: repeat(12, 1fr);
                opacity: .15"
        >
            <div
                v-for="rainForecast in rainForecasts"
                class="rounded-sm bg-accent"
                :style="`height:${rainForecast.rain * 100}%`"
            />
        </div>
    </tile>
</template>

<script>
import { emoji } from '../helpers';
import echo from '../mixins/echo';
import Tile from './atoms/Tile';
import moment from 'moment-timezone';
import weather from '../services/weather/Weather';
import OfficeTemperature from './atoms/OfficeTemperature';

export default {
    components: {
        OfficeTemperature,
        Tile,
    },

    mixins: [echo],

    props: {
        weatherCity: {
            type: String,
        },
        dateFormat: {
            type: String,
            default: 'DD-MM-YYYY',
        },
        timeFormat: {
            type: String,
            default: 'HH:mm',
        },
        timeZone: {
            type: String,
        },
        position: {
            type: String,
        },
    },

    data() {
        return {
            date: '',
            time: '',
            weather: {
                temperature: '',
                iconClass: '',
            },
            rainForecasts: []
        };
    },

    created() {
        this.refreshTime();
        setInterval(this.refreshTime, 1000);

        this.fetchWeather();
        setInterval(this.fetchWeather, 15 * 60 * 1000);
    },

    methods: {
        emoji,

        refreshTime() {
            this.date = moment()
                .tz(this.timeZone)
                .format(this.dateFormat);
            this.time = moment()
                .tz(this.timeZone)
                .format(this.timeFormat);
        },

        getEventHandlers() {
            return {
                'Buienradar.ForecastsFetched': response => {
                    this.rainForecasts = response.forecasts;
                },
            };
        },

        async fetchWeather() {
            const conditions = await weather.conditions(this.weatherCity);

            let icon;

            switch (parseInt(conditions.code)) {
                case 0:
                case 1:
                case 2:
                    icon = '🌪';
                    break;
                case 3:
                case 4:
                case 37:
                case 38:
                case 39:
                case 45:
                case 47:
                    icon = '⛈';
                    break;
                case 5:
                case 6:
                case 7:
                case 8:
                case 9:
                case 10:
                case 17:
                case 18:
                    icon = '🌨';
                    break;
                case 11:
                case 12:
                case 35:
                case 40:
                    icon = '☔️';
                    break;
                case 13:
                case 14:
                case 15:
                case 16:
                case 42:
                case 46:
                    icon = '❄️';
                    break;
                case 19:
                case 20:
                case 21:
                case 22:
                    icon = '🌫';
                    break;
                case 23:
                case 24:
                case 25:
                    icon = '💨';
                    break;
                case 26:
                    icon = '☁️';
                    break;
                case 27:
                case 28:
                case 29:
                case 30:
                case 44:
                    icon = '⛅️';
                    break;
                case 31:
                case 33:
                    icon = '🌌';
                    break;
                case 32:
                case 34:
                    icon = '☀️';
                    break;
                case 36:
                    icon = '🌡';
                    break;
                case 41:
                case 43:
                    icon = '⛷';
                    break;
                default:
                    icon = '🧐';
            }

            this.weather.temperature = conditions.temp;
            this.weather.icon = emoji(icon);
        },
    },
};
</script>
