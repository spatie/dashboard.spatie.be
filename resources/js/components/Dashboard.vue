<template>
    <div
        class="fixed pin grid gap-spacing w-screen h-screen p-spacing font-sans font-normal leading-normal text-default bg-screen"
        :class="mode"
    >
        <slot></slot>
    </div>
</template>

<script>
import echo from '../mixins/echo';
import saveState from 'vue-save-state';

export default {
    mixins: [echo, saveState],

    props: {
        mode: {
            type: String,
            default: 'light-mode',
        },
    },

    methods: {
        getEventHandlers() {
            return {
                'Dashboard.UpdateAppearance': response => {
                    this.mode = response.mode;
                },
            };
        },
    },
};
</script>
