<?php
session_start();

if (!isset($_SESSION["role"])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION["role"] != "root" && $_SESSION["role"] != "admin") {
    header("Location: user.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard.css">
    <title>Dashboard</title>
</head>
<body>

    <h1>Welcome, <?php echo $_SESSION["username"]; ?>!</h1>

    <p>You are logged in as: <?php echo $_SESSION["role"]; ?></p>

    <h2>Management Panel</h2>

    <button onclick="window.location.href='userManagement.php'">
        User Management
    </button>

    <button onclick="window.location.href='role.php'">
        Role Management
    </button>

    <br><br>

    <button onclick="window.location.href='logout.php'">
        Logout
    </button>

</body>
</html>