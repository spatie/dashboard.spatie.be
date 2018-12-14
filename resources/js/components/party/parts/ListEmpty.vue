<template>
    <div class="p-3 rounded-full border-accent border-2 border-dashed mt-3" style="margin-right: 12px;">
        <div class="flex justify-between">
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
import { emoji } from '../../../helpers';

export default {
    methods: {
      emoji,
    },
}
</script>