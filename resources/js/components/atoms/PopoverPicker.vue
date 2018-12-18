<template>
    <div class="modal_6hx5" :id="eleId" v-show="status.visible"> 
        <header class="flex justify-between items-end">
            <button class="_close"><i v-html="emoji('✖️')" v-on:click="close" /></button>
            <span class="py-1 px-3 _title">Select</span>
            <button class="bg-warn py-1 px-1 _done" v-on:click="done">Done</button>
        </header>
        <section class="pt-3">
            <ul>
                <li v-for="item in data" 
                    v-on:click="select(item)"
                    class="py-1 mx-2 border-b border-screen grid"
                    style="grid-template-columns: 1fr auto;">
                    <span>{{item.label}}</span>
                    <span v-show="item.checked"><i v-html="emoji('✅')" /></span>
                </li>
            </ul>
        </section>
    </div>
</template>

<style>
.modal_6hx5 {
    background-color: rgba(0, 0, 0, 0.6);
}
.modal_6hx5 header {
    height: 15%;
}
.modal_6hx5 header > * {
    line-height: 1;
}
.modal_6hx5 section {
    position: absolute;
    bottom: 0;
    width: 100%;
    height: 85%;
    background-color: #fff;
    
}
.modal_6hx5 ul {
    overflow-y: scroll;
    max-height: 100%;
}
.modal_6hx5 ._close {
    background-color: #fff;
    width: 20px;
    height: 20px;
    border-top-right-radius: 0.25rem;
}
.modal_6hx5 ._title {
    background-color: #fff;
    border-top-left-radius: 0.25rem;
    border-top-right-radius: 0.25rem;
}
.modal_6hx5 ._done {
    border-top-left-radius: 0.25rem;
}

</style>

<script>
import {emoji, uuid} from '../../helpers';

export default {
    props: ['status', 'data'],

    data() {
        return {
            eleId: uuid(4),
        }
    },

    methods: {
        emoji,
        close() {
            this.status.visible = false;
        },
        select(item) {
            item.checked = !item.checked;
        },
        done() {
            let result = [];
            for (let opt of this.data) {
                if (opt.checked) {
                    result.push(opt);
                }
            }

            this.$emit('submit', result);
            this.close();
        }
    },

    mounted() {
        this.$emit('createdto', this.eleId);

        //move element to outer .the-tile
        let ele = document.getElementById(this.eleId);
        let tile = ele.closest('.the-tile');

        if (tile) {
            tile.append(ele);
        }
    },
}
</script>