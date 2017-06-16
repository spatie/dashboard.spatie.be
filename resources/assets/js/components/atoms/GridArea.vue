<template>
    <div :class="gridAreaLook" :style="gridAreaPosition">
        <slot></slot>
    </div>
</template>

<script>
    import { addClassModifiers, positionToGridAreaNotation } from '../../helpers';

    export default {

        props: ['position', 'modifiers'],

        computed: {
            gridAreaPosition() {
                return `grid-area: ${positionToGridAreaNotation(this.position)}`;
            },

            gridAreaLook() {
                return addClassModifiers('grid__area', this.modifiers);
            },
        },
    };
</script>
