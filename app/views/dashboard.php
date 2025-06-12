<!-- app/views/dashboard.php -->
<?php include 'header.php'; ?>
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
<!-- cumhefftbnxvul2j7s1d8g0wo3t5p2zsbpboptgpnxqdlgw5 -->