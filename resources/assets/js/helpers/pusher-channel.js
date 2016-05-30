import Pusher from 'pusher-js';

const pusher = new Pusher('09cf1f7e4f142dbbf397', {
    cluster: 'eu',
    authEndpoint: '/pusher/authenticate',
});

const pusherChannel = pusher.subscribe('private-dashboard');

export default pusherChannel;