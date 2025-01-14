const express = require('express');
const app = express();
const http = require('http');
const server = http.createServer(app);
const { Server } = require("socket.io");
const io = new Server(server);

io.on('connection', (socket) => {
    console.log('a user connected');
    socket.on('like_updated', (data) => {
        io.emit('update_like_count', data);
    });
});

server.listen(3000, () => {
    console.log('listening on *:3000');
});