<template>
    <screen v-on:close="onClose">
        <div slot="header" class="flex flex-start">
            <input type="text" name="event_name" placeholder="Event name" id="input_98fu" />
            <button title="Pick template" class="btn_24hy">
                <div v-html="emoji('🗒️')"></div>
            </button>
        </div>
        <people-picker :people="peopleData" v-on:updated="calculateMoney" />

        <div class="border-t border-dashed border-screen mt-3 pt-4">
            <div class="flex flex-between">
                <i class="badget_jxh2" v-html="emoji('💰')" />
                <div class="px-2">
                    <input type="text" name="fee" placeholder="Fee/Cost" 
                        class="border-b border-screen pb-1" style="width: 120px;" 
                        v-model.lazy="feeMasked" 
                        v-money="vnd" 
                        v-on:change="fee = strToNumber(feeMasked)" />
                    <input type="hidden" name="is_pax" v-model="inputIsPax" />
                </div>
                <div v-on:click="inputIsPax = !inputIsPax">
                    <i v-html="emoji('◻️')" class="check_zsn2" v-show="!inputIsPax" /> 
                    <i v-html="emoji('◼️')" class="check_zsn2" v-show="inputIsPax" /> 
                    <span class="text-xs">PAX</span>
                    <span class="text-xs" v-show="!inputIsPax">?</span>
                </div>
            </div>
            <p class="text-right text-xs" v-if="fee > 0">
                <span v-if="inputIsPax">(Total: </span>
                <span v-if="!inputIsPax">(Each: </span>
                <input class="text-right" type="text" readonly size="11" v-model.lazy="feeSumary" v-money="vnd" />
                <span>)</span>
            </p>
        </div>
    </screen>
</template>

<style>
#input_98fu {
    border-bottom: 1px solid #8795A1;
}
.btn_24hy {
    width: 1.2em;
}
.btn_24hy .emoji {
    height: 1.2em;
}
.badget_jxh2 {
    width: 20px;
    height: 20px;
    display: inline-block;
    background-color: var(--bg-screen);
}
.check_zsn2 {
    width: 18px;
    height: 18px;
    display: inline-block;
}
</style>

<script>
import {VMoney} from 'v-money';
import {emoji, strToNumber} from '../../helpers';
import routes from './services/route';
import EventBus from '../../services/event-bus';
import Screen from './parts/Screen';
import PeoplePicker from '../atoms/PeoplePicker';

export default {
    components: {
        Screen,
        PeoplePicker,
    },

    methods: {
        emoji,
        strToNumber,
        onClose() {
            EventBus.$emit('party:navigate', routes.EMPTY);
        },
        calculateMoney() {
            this.feeSumary = this.inputIsPax 
                ? this.fee * this.peopleData.list.length
                : this.fee / this.peopleData.list.length;
        }
    },

    data() {
        return {
            fee: 0,
            feeMasked: '',
            feeSumary: 0,
            vnd: {
                thousands: '.',
                suffix: ' Đ',
                precision: 0,
                masked: false
            },
            inputIsPax: false,
            peopleData: {
                list: [],
            }
        }
    },

    directives: {money: VMoney},

    watch: {
        fee() {
            this.calculateMoney();
        },
        inputIsPax() {
            this.calculateMoney();
        }
    },
}
</script>