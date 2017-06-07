<template>
    <grid :position="grid">
        <section class="current-time">
            <time class="current-time__content">
                <span class="current-time__time">{{ time }}</span>
                <span class="current-time__date">{{ date }}</span>
            </time>

            Temperature: {{ weather.temperature }}
            Description: {{ weather.description }}
        </section>

    </grid>
</template>

<script>
import Grid from './atoms/Grid';
import moment from 'moment';
import weather from '../services/weather/Weather';

export default {

    components: {
        Grid,
    },

    props: {
        dateformat: {
            type: String,
            default: 'DD-MM-YYYY',
        },
        timeformat: {
            type: String,
            default: 'HH:mm:ss',
        },
        grid: {
            type: String,
        },
    },

    data() {
        return {
            date: '',
            time: '',
            weather: {
                temperature: '',
                description: '',
            },
        };
    },

    created() {
        this.refreshTime();
        setInterval(this.refreshTime, 1000);

       this.fetchWeather();
       setInterval(this.fetchWeather, 15 * 60 * 1000)
    },

    methods: {
        refreshTime() {
            this.date = moment().format(this.dateformat);
            this.time = moment().format(this.timeformat);
        },

        async fetchWeather() {
            const conditions = await weather.conditions();

            this.weather.temperature = conditions.temp;
            this.weather.description = conditions.text;
        },
    },
};


</script>
