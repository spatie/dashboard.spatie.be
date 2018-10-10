<template>
    <div :style="tilePosition" class="tile">
        <div class="tile-content">
            <slot></slot>
        </div>
    </div>
</template>

<script>
import { positionToGridAreaNotation } from '../../helpers';

export default {
    props: ['position'],

    computed: {
        tilePosition() {
            return `grid-area: ${positionToGridAreaNotation(this.position)}`;
        },
    },
};
</script>
