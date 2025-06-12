const { spawn } = require("child_process");

const phpServer = spawn("php", ["-S", "localhost:8080", "router.php"], {
  stdio: "inherit",
  shell: true
});

const nodeWatcher = spawn("node", ["dev-server.js"], {
  stdio: "inherit",
  shell: true
});
