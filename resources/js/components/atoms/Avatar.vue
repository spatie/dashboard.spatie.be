<template>
    <div class="flex-none overflow-hidden w-8 h-8 rounded-full border-2 border-screen">
        <img class="filter-grey block w-8 h-8" :src="src" style="object-fit: cover;" />
        <div class="absolute pin bg-accent opacity-25"></div>
    </div>
</template>

<script>
export default {
    props: ['src'],
};
</script>
