const chokidar = require("chokidar");
const WebSocket = require("ws");
const http = require("http");

const server = http.createServer();
const wss = new WebSocket.Server({ server });

function notifyClients() {
  wss.clients.forEach(client => {
    if (client.readyState === WebSocket.OPEN) {
      client.send("reload");
    }
  });
}

const watcher = chokidar.watch([
  "./public/**/*.php",     // Pages
  "./app/**/*.php",        // Controllers / Models
  "./views/**/*.php",      // Views
  "./routes/**/*.php",     // Routes (if any)
], {
  ignored: /node_modules/,
  usePolling: true,
  interval: 100,
  persistent: true,
});

watcher.on("change", (path) => {
  console.log("📦 File changed:", path);
  notifyClients();
});

server.listen(3001, () => {
  console.log("🚀 Dev server running at ws://localhost:3001");
});