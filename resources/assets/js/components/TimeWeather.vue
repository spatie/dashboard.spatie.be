<template>
    <grid :position="grid">
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
                this.date = moment().format(this.dateformat);
                this.time = moment().format(this.timeformat);
            },

            async fetchWeather() {
                const conditions = await weather.conditions();

                this.weather.temperature = conditions.temp;
                this.weather.iconClass = 'wi-yahoo-' + conditions.code;
            },
        },
    };
</script>
