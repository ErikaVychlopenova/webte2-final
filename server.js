const WebSocket = require('ws');
const https = require('https');
const fs = require('fs');

const server = https.createServer({
    cert: fs.readFileSync("/home/xvychlopenova/webte.fei.stuba.sk-chain-cert.pem"),
    key: fs.readFileSync("/home/xvychlopenova/webte.fei.stuba.sk.key")

})

server.listen(9000)

const ws = new WebSocket.Server({server});

const messages = [];
const users = [];
let id = 1;

ws.on('connection', (socket) => {
    console.log("new connection");
    socket.id = id++;

    const msg = {"event": 'new_user', "role": 'visitor', "id": socket.id};
    console.log(msg);
    users.push(msg);
    socket.send(JSON.stringify(msg));

    messages.forEach(message => {
        socket.send(message);
    })

    socket.on("message", (data) => {
        const msg = JSON.parse(data.toString());
        msg.id = socket.id;
        messages.push(JSON.stringify(msg));
        console.log(msg);
        ws.clients.forEach(client => {
            client.send(JSON.stringify(msg));
        })
    })
})