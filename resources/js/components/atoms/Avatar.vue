<template>
    <div class="flex-none overflow-hidden w-8 h-8 rounded-full mr-3">
        <div v-if="darkmode">
            <img class="filter-grey block w-8 h-8" :src="src" style="object-fit: cover;" />
            <div class="absolute pin bg-accent opacity-25"></div>
        </div>
        <div v-else>
            <img class="block w-8 h-8" :src="src" style="object-fit: cover;" />
        </div>
    </div>
</template>

<script>
export default {
    props: ['src', 'darkmode'],
};
</script>
