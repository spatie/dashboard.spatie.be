<template>
    <a :href="url" 
        class="a_nawbx px-1 mr-1 border-2 border-screen rounded text-xs hover:border-accent hover:text-accent"
        target="_blank"
        rel="noopener noreferrer"
        :style="hint ? 'border-color: var(--bg-accent);' : ''"
        >
        {{ jkey }}

        <span class="bg-default text-invers" v-if="hint">{{hint}}</span>
    </a>
</template>

<style>
.a_nawbx {
    position: relative;
    display: inline-block;
}
.a_nawbx span {
    position: absolute;
    top: -25px;
    left: 0;
    height: 18px;
    font-size: 12px;
    padding: 0 4px;
    white-space: nowrap;
    display: none;
}
.a_nawbx:hover span {
    display: block;
}
</style>

<script>
export default {
    props: ['jkey', 'hint'],

    data() {
        return {
            url: 'https://coccoc.atlassian.net/browse/'+this.jkey,
        }
    },
}
</script>