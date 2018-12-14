<template>
    <screen v-on:close="close">
        <div slot="header" class="flex flex-start">
            <input type="text" placeholder="Event name" id="input_98fu" v-model="eventName" />
            <button title="Pick template" class="btn_24hy">
                <div v-html="emoji('🗒️')"></div>
            </button>
        </div>
        <people-picker :people="peopleData" v-on:updated="prepareForm" />

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
            <p class="text-left text-xs" v-if="fee > 0">
                <span v-if="inputIsPax">(Total: </span>
                <span v-if="!inputIsPax">(Each: </span>
                <input class="text-right" type="text" readonly size="11" v-model.lazy="feeSumaryMasked" v-money="vnd" />
                <span>)</span>
            </p>
        </div>

        <div slot="footer" class="footer_znwb">
            <button class="w-full py-2 text-xs" 
                :class="!formReady ? 'bg-screen cursor-not-allowed' : 'bg-warn text-invers'"
                :disabled="!formReady" 
                v-on:click="submit">
                SAVE
            </button>
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
import axios from 'axios';
import {VMoney} from 'v-money';
import {emoji, strToNumber} from '../../helpers';
import routes from './services/route';
import EventBus from '../../services/event-bus';
import Screen from '../atoms/Screen';
import PeoplePicker from '../atoms/PeoplePicker';

export default {
    components: {
        Screen,
        PeoplePicker,
    },

    methods: {
        emoji,
        strToNumber,
        close() {
            EventBus.$emit('party:navigate', routes.LIST);
        },
        prepareForm() {
            let count = this.peopleData.list.length;

            if (this.inputIsPax) {
                this.feeSumary = parseInt(this.fee * count);
            }
            else {
                this.feeSumary = count ? parseInt(this.fee / count) : 0;
            }

            this.feeSumaryMasked = this.feeSumary;
            this.formReady = !!(this.fee && this.eventName);
        },
        submit : async function() {
            const api_url = '/party';
            const self = this;

            let payload = {
                name: this.eventName,
                admin: 'quypv1',
            };

            if (this.inputIsPax) {
                payload.pax = this.fee;
                payload.total = this.feeSumary;
            }
            else {
                payload.pax = this.feeSumary;
                payload.total = this.fee;
            }

            return await axios.post(api_url, payload)
                .then(res => {
                    self.close();
                })
                .catch(error => {
                    console.log(error);
            });
        },
    },

    data() {
        return {
            peopleData: {
                list: [],
            },
            formReady: false,
            feeSumary: 0,
            feeSumaryMasked: 0,
            fee: 0,
            feeMasked: '',
            eventName: '',
            vnd: {
                thousands: '.',
                suffix: ' Đ',
                precision: 0,
                masked: false
            },
            inputIsPax: false,
        }
    },

    directives: {money: VMoney},

    watch: {
        fee() { this.prepareForm() },
        inputIsPax() { this.prepareForm() },
        eventName() { this.prepareForm() },
    },
}
</script>