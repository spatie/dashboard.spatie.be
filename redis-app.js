var app = require('http').createServer(handler);
var io = require('socket.io')(app);

var Redis = require('ioredis');
var redis = new Redis();

app.listen(6002, function () {
});

function handler(req, res) {
    res.writeHead(200);
    res.end('');
}

io.on('connection', function (socket) {
});

redis.psubscribe('*', function (err, count) {
});

redis.on('*', function (subscribed, channel, message) {
});

redis.on('pmessage', function (subscribed, channel, message) {
    message = JSON.parse(message);
    io.emit(message.event, message.data);
});
