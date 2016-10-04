<template>
    <div :class="gridFromTo(position)">
        <div :class="modifyClass(modifiers, 'grid__tile')">
             <slot></slot>
        </div>
    </div>
</template>

<script>
import { gridFromTo, modifyClass } from '../helpers';

export default {

    props: ['modifiers', 'position'],

    methods: {
        gridFromTo,
        modifyClass,
    },

};
</script>
