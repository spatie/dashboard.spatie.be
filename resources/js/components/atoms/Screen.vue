<template>
    <div class="the-screen h-full">
        <header class="flex flex-start mb-5">
            <button class="btn-mn29 mr-2" v-if="!noBack">
                <i v-html="emoji('🔙')" v-on:click="$emit('close')" />
            </button>
            <slot name="header"></slot>
        </header>
        <div class="_body">
            <slot></slot>
        </div>
        <footer class="mt-5 w-full">
            <slot name="footer"></slot>
        </footer>
    </div>
</template>

<style>
.btn-mn29 .emoji{
    height: 1.5em;
    margin-left: 0;
}
.btn-mn29 {
    height: 1.5em;
    line-height: 1;
}
.btn-mn29 i {
    width: 18px;
    display: inline-block;
}
.the-screen {
    overflow-y: auto;
}
</style>

<script>
import { emoji } from '../../helpers';

export default {
    props: {
        footerH: { default: 5 },
        noBack: { default: false },
    },

    methods: {
      emoji,
    },
}
</script>