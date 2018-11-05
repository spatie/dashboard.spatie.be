<template>
    <tile :position="position">
        <div class="grid justify-items-center h-full" style="--template-rows: auto 1fr auto; --gap: 1rem">
            <div class="markup">
                    <h1>{{ date }}</h1>
            </div>
            <div class="align-self-center font-bold text-5xl tracking-wide leading-none">
                {{ time }}
            </div>
            <div class="uppercase">
                <div class="grid" style="--template-cols: repeat(3, auto); --gap: 1rem">
                    <span>
                        {{ weather.temperature }}°
                        <span class="text-sm uppercase text-dimmed">out</span>
                    </span>
                    <span>
                        <office-temperature />
                        <span class="text-sm uppercase text-dimmed">in</span>
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
