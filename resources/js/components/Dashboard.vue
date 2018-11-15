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

export default {
    mixins: [echo],

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
                    this.mode = `{$response.name}-mode`;
                },
            };
        },
    },
};
</script>
