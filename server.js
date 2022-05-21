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

ws.on('connection', (socket) => {
    messages.forEach(message => {
        socket.send(message);
    })

    socket.on("message", (data) => {
        const msg = JSON.parse(data.toString());
        messages.push(JSON.stringify(msg));
        console.log(msg);
        ws.clients.forEach(client => {
            client.send(JSON.stringify(msg));
        })
    })
})