<template>
    <grid :position="grid" modifiers="overflow transparent">
        <section class="tweets">
            <!-- <div>user: {{ twitterUsername }}  tweet: {{ tweetText }}</div> -->
            <div class="tweet">
                <div class="tweet__header">
                    <div class="tweet__avatar"
                         style="background-image: url(https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?s=512)"></div>
                    <div class="tweet__user">
                        <div class="tweet__user__name">
                            Firstname Lastname
                        </div>
                        <div class="tweet__user__handle">
                            @averylongusernamethatneedscutoff
                        </div>
                    </div>
                </div>
                <div :class="addClassModifiers('tweet__body', 'medium')">
                    <span class="tweet__body__handle">@username</span> Donec mi nibh, consectetur sit amet suscipit
                    vitae, fringilla quis orci <span class="tweet__body__hashtag">#topic</span>
                </div>
                <div class="tweet__meta">
                    2 minutes ago
                </div>
                <div class="tweet__attachment">
                    <img src="http://placehold.it/800x600">
                </div>
            </div>

            <div class="tweet">
                <div class="tweet__header">
                    <div class="tweet__avatar"
                         style="background-image: url(https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?s=512)"></div>
                    <div class="tweet__user">
                        <div class="tweet__user__name">
                            Firstname Lastname
                        </div>
                        <div class="tweet__user__handle">
                            @averylongusernamethatneedscutoff
                        </div>
                    </div>
                </div>
                <div :class="addClassModifiers('tweet__body', 'default')">
                    <span class="tweet__body__handle">@username</span> Donec mi nibh, consectetur sit amet suscipit
                    vitae, fringilla quis orci <span class="tweet__body__hashtag">#topic</span>
                </div>
                <div class="tweet__meta">
                    2 minutes ago
                </div>
                <div class="tweet__attachment">
                    <img src="http://placehold.it/400x1200">
                </div>
            </div>

            <div class="tweet">
                <div class="tweet__header">
                    <div class="tweet__avatar"
                         style="background-image: url(https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?s=512)"></div>
                    <div class="tweet__user">
                        <div class="tweet__user__name">
                            Firstname Lastname
                        </div>
                        <div class="tweet__user__handle">
                            @averylongusernamethatneedscutoff
                        </div>
                    </div>
                </div>
                <div :class="addClassModifiers('tweet__body', 'small')">
                    <span class="tweet__body__handle">@username</span> Donec mi nibh, consectetur sit amet suscipit
                    vitae, fringilla quis orci <span class="tweet__body__hashtag">#topic</span>
                </div>
                <div class="tweet__meta">
                    2 minutes ago
                </div>
                <div class="tweet__attachment">
                    <img src="http://placehold.it/400x1200">
                </div>
            </div>

        </section>
    </grid>
</template>

<script>
    import echo from '../mixins/echo';
    import Grid from './Grid';
    import saveState from 'vue-save-state';
    import {addClassModifiers} from '../helpers';

    export default {

        components: {
            Grid,
        },

        mixins: [echo, saveState],

        props: ['grid'],

        data() {
            return {
                twitterUsername: '',
                tweetText: '',
            };
        },

        methods: {
            addClassModifiers,

            getEventHandlers() {
                return {
                    'Twitter.Mentioned': response => {
                        this.twitterUsername = response.twitterUsername;
                        this.tweetText = response.tweetText;
                    },
                };
            },

            getSaveStateConfig() {
                return {
                    cacheKey: `twitter`,
                };
            },
        },
    };
</script>
