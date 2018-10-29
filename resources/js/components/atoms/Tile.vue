<template>
    <div :style="tilePosition" class="grid">
        <div :class="transparent ? '' : 'overflow-hidden rounded bg-tile'">
            <div :class="transparent ? '' : 'absolute pin overflow-auto p-gap'">
                <slot></slot>
            </div>
            <div v-if="!transparent" class="absolute pin pointer-events-none shadow-tile-inner"></div>
        </div>
    </div>
</template>

<script>
import { positionToGridAreaNotation } from '../../helpers';

export default {
    props: ['position', 'transparent'],

    computed: {
        tilePosition() {
            return `grid-area: ${positionToGridAreaNotation(this.position)}`;
        },
    },
};
</script>
