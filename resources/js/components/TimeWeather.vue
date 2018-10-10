<template>
    <tile :position="position" >
        <div class="grid grid-rows grid-gap grid-areas justify-items-center h-full" style="--gap: 1rem; --template-rows: 1fr auto 1fr; --areas: 'empty' 'content' 'location';">
            <div class="grid grid-area justify-items-center" style="--area: content;">
                <div class="uppercase">
                    {{ date }}
                </div>
                <div class="font-bold text-5xl tracking-wide leading-none text-red">
                    {{ time }}
                </div>
            </div>
            <div class="grid-area align-self-end uppercase" style="--area: location;">
                <div class="grid grid-cols grid-gap" style="--template-cols: repeat(3, auto); --gap: 1rem">
                    <span>
                        {{ weather.temperature }}°
                        <span class="text-sm uppercase text-grey-dark">out</span>
                    </span>
                    <span>
                        <office-temperature />
                        <span class="text-sm uppercase text-grey-dark">in</span>
                    </span>
                    <i class="align-self-center wi" :class="weather.iconClass"></i>
                </div>
                <div class="hidden">
                    {{ weatherCity }}
                </div>
            </div>
        </div>
    </tile>
</template>

<script>
import Tile from './atoms/Tile';
import moment from 'moment-timezone';
import weather from '../services/weather/Weather';
import OfficeTemperature from "./atoms/OfficeTemperature";

export default {
    components: {
        OfficeTemperature,
        Tile,
    },

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
        };
    },

    created() {
        this.refreshTime();
        setInterval(this.refreshTime, 1000);

        this.fetchWeather();
        setInterval(this.fetchWeather, 15 * 60 * 1000);
    },

    methods: {
        refreshTime() {
            this.date = moment()
                .tz(this.timeZone)
                .format(this.dateFormat);
            this.time = moment()
                .tz(this.timeZone)
                .format(this.timeFormat);
        },

        async fetchWeather() {
            const conditions = await weather.conditions(this.weatherCity);

            this.weather.temperature = conditions.temp;
            this.weather.iconClass = `wi-yahoo-${conditions.code}`;
        },
    },
};
</script>
