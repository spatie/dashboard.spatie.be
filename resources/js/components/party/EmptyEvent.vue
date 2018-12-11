<template>
    <div>
        <title-text>No upcoming event</title-text>
        <div 
            class="grid p-3 rounded-full border-accent border-2 border-dashed mt-3" 
            style="grid-template-columns: 1fr auto; margin-right: 12px;" 
            >
            <span class="text-accent">Host a new one!</span>
            <span class="-ml-2">
                <img 
                    src="https://p1performance.com/wp-content/uploads/2018/04/red-arrow-3.png" 
                    width="30"
                    style="transform: rotate(-52deg);" />
            </span>
        </div>
    </div>
</template>

<script>
import TitleText from './parts/TitleText';
import routes from './services/route';

export default {
    components: {
        TitleText,
    },

    methods: {
    },
}    
</script>