<template>
    <span v-if="show" class="time-weather__weather__temperature">
        <span>{{ temperature }}</span>
    </span>
</template>

<script>
    import echo from '../../mixins/echo';
    import saveState from 'vue-save-state';

    export default {
        mixins: [echo, saveState],

        computed: {
            show() {
                return this.temperature.length;
            }
        },

        data() {
            return {
                temperature: null,
            };
        },

        methods: {
            getEventHandlers() {
                return {
                    'Temperature.TemperatureFetched': response => {
                        this.temperature = response.temperature;
                    },
                };
            },

            getSaveStateConfig() {
                return {
                    cacheKey: `temperature`,
                };
            },
        },
    }
</script>
