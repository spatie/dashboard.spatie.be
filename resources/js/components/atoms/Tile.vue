<template>
    <div :class="tileLook" :style="tilePosition">
        <slot></slot>
    </div>
</template>

<script>
import { addClassModifiers, positionToGridAreaNotation } from '../../helpers';

export default {
    props: ['position', 'modifiers'],

    computed: {
        tilePosition() {
            return `grid-area: ${positionToGridAreaNotation(this.position)}`;
        },

        tileLook() {
            return addClassModifiers('tile', this.modifiers);
        },
    },
};
</script>
