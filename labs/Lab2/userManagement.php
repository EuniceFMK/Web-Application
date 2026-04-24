<?php
session_start();

 if(!isset($_SESSION["role"]) || 
    ($_SESSION["role"] != "root" && $_SESSION["role"] != "admin")) {
    header("Location: index.php");
    exit();
 } 
?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="userManagement.js"></script>
    <link rel="stylesheet" href="root.css">
    <title>Document</title>
</head>
<body>
    <form action="">
        <h1>Welcome, <?php echo $_SESSION["username"]; ?>! to the user management panel</h1>
        <p>This is the root dashboard. You have full access to the system.</p>
        <button type="button" onclick="window.location.href='logout.php'">Logout</button>

        <div class="input-box">
            <label for="username">Username</label>
            <input id="username" type="text" name="username" >
        </div>

        <div class="input-box">
            <label for="password">Password</label>
            <input id="password" type="password" name="password">
        </div>

        <div class="input-box">
            <label for="roles">Roles</label>
            <select id="roles" name="roles">
                <option value="1">Root</option>
                <option value="2">Admin</option>
                <option value="3">Member</option>
            </select>
        </div>

        <div>
        <button type="button" class="add-user-btn" id="addUserBtn">Register New User</button>
        </div>

        <div>
            <table>
                <thead>
                    <tr>
                        <th>Operation</th>
                        <th>UserId</th>
                        <th>Username</th>
                        <th>Hashed Password</th>
                        <th>Change Role</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    
                </tbody>
            </table>
        </div>
        <div id="message" style="display:none;">

        </div>
    </form>
</body>
</html>