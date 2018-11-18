<template>
    <span v-if="temperature">{{ temperature }}°</span>
</template>

<script>
import echo from '../../mixins/echo';
import saveState from 'vue-save-state';

export default {
    mixins: [echo, saveState],

    data() {
        return {
            temperature: 21,
        };
    },

    methods: {
        getEventHandlers() {
            return {
                'TimeWeather.TemperatureFetched': response => {
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
};
</script>
