<template>
    <tile :position="position" modifiers="overflow">
        <section class="time-weather">
            <time class="time-weather__content">
                <span class="time-weather__date">{{ date }}</span>
                <span class="time-weather__time">{{ time }}</span>
                <span class="time-weather__weather">
                    <span class="time-weather__weather__temperature">{{ weather.temperature }}</span>
                    <span class="time-weather__weather__description">
                        <i class="wi" :class="weather.iconClass"></i>
                    </span>
                </span>
            </time>
            <span class="time-weather__time-zone">{{ weatherCity }}</span>
        </section>
    </tile>
</template>

<script>
import Tile from './atoms/Tile';
import moment from 'moment-timezone';
import weather from '../services/weather/Weather';

export default {
    components: {
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
