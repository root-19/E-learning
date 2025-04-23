<!-- app/views/dashboard.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
    <p>This is your dashboard.</p>
    <a href="/logout">Logout</a>

    <script>
const socket = new WebSocket("ws://localhost:3001");
socket.onmessage = (event) => {
  if (event.data === "reload") {
    console.log("♻ Reloading page...");
    location.reload();
  }
};
</script>
</body>
</html>
