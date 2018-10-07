<template>
    <span v-if="temperature">{{ temperature }}</span>
</template>

<script>
    import echo from '../../mixins/echo';
    import saveState from 'vue-save-state';

    export default {
        mixins: [echo, saveState],

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
