import { forIn } from 'lodash';

export default {
    created() {

        forIn(this.getEventHandlers(), (handler, event) => {
            Echo.channel('dashboard')
                .listen(event, (event) => {
                    handler(event).bind(this);
                });
        });
    },
};
