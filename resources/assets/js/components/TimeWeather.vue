<template>
    <tile :position="position">
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
    </tile>
</template>

<script>
    import Tile from './atoms/Tile';
    import moment from 'moment';
    import weather from '../services/weather/Weather';

    export default {

        components: {
            Tile,
        },

        props: {
            dateFormat: {
                type: String,
                default: 'DD-MM-YYYY',
            },
            timeFormat: {
                type: String,
                default: 'HH:mm:ss',
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
                this.date = moment().format(this.dateFormat);
                this.time = moment().format(this.timeFormat);
            },

            async fetchWeather() {
                const conditions = await weather.conditions();

                this.weather.temperature = conditions.temp;
                this.weather.iconClass = `wi-yahoo-${conditions.code}`;
            },
        },
    };
</script>
