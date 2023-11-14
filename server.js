var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http, {
    serveClient: false,
    cors: {
        origin: "http://chat.loc"
    }
});
var Redis = require('ioredis');
var redis = new Redis();
var users = {};

function getKeyByValue(object, value) {
    return Object.keys(object).find(key => object[key] === value);
}

http.listen(8005, () => {
    console.log("Listening to 8005 port");
});

redis.subscribe('private-channel', () => {
    console.log('subscribed to private channel');
});

const videos = [
    {
        id: 1,
        img_url: "asdasdasd",
        video_url: "ashdsahd",
        title: "Title 1",
        channel_name: "Pupsdsd",
    },
    {
        id: 2,
        video_url: "asdsa",
        title: "dsfsdf 1",
        channel_name: "Pupsdsd",
    },
]

redis.on('message', (channel, message) => {
    message = JSON.parse(message);
    if (channel === 'private-channel') {
        let data = message.data.data;
        let receiver_id = data.receiver_id;
        let event = message.event;

        io.to(`${users[receiver_id]}`).emit(channel + ':' + event, data);
    }
});

io.on("connection", (socket) => {

    socket.on("user_connected", (user_id) => {
        users[user_id] = socket.id;
        io.sockets.emit('updateUserStatus', users);
    });

    socket.on("write", (data) => {
        console.log("write ", data);
        io.to(`${users[data.receiver_id]}`).emit('write', data);
    });

    socket.on('disconnect', () => {
        var i = getKeyByValue(users, socket.id);
        delete users[i];
        io.sockets.emit('updateUserStatus', users);

        socket.disconnect();
    });

});
