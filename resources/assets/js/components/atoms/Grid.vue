<template>
    <div :class="gridClass">
        <div :class="tileClass">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    import { addClassModifiers } from '../../helpers';

    export default {

        props: ['modifiers', 'position'],

        computed: {
            gridClass() {
                const [from, to = from] = this.position.toLowerCase().split(':');

                return addClassModifiers('grid', [`from-${from}`, `to-${to}`]);
            },

            tileClass() {
                return addClassModifiers('grid__tile', this.modifiers);
            },
        },
    };
</script>
