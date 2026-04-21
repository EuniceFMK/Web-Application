<?php
session_start();

if(!isset($_SESSION["role"]) || $_SESSION["role"] != "root"){
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Role Management</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="roleManagement.js"></script>
</head>

<body>

<h1>Role Management</h1>

<input type="text" id="roleName" placeholder="Role Name">
<input type="text" id="roleDesc" placeholder="Description">
<input type="number" id="roleValue" placeholder="Value">

<button onclick="addRole()">Add Role</button>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Value</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="rolesTable"></tbody>
</table>

</body>
</html>