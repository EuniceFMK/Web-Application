<?php
session_start();

if (
    !isset($_SESSION["role"]) ||
    ($_SESSION["role"] != "root" && $_SESSION["role"] != "admin")
) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role Management</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="rolemanagement.js"></script>

    <link rel="stylesheet" href="rolemanagement.css">
</head>
<body>

    <div class="container">

        <h1>Role Management</h1>
        <p>Manage all system roles from this panel</p>

        <div class="form-section">
            <input
                type="text"
                id="roleName"
                placeholder="Role Name"
            >

            <input
                type="text"
                id="roleDesc"
                placeholder="Role Description"
            >

            <input
                type="number"
                id="roleValue"
                placeholder="Role Value"
            >

            <button
                type="button"
                class="add-role-btn"
                onclick="addRole()"
            >
                Add New Role
            </button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Role ID</th>
                    <th>Role Name</th>
                    <th>Role Description</th>
                    <th>Role Value</th>
                    <th>Operation</th>
                </tr>
            </thead>

            <tbody id="rolesTable">
                <!-- Loaded by JS -->
            </tbody>
        </table>

        <div id="message"></div>

        <button
            type="button"
            class="back-btn"
            onclick="window.location.href='dashboard.php'"
        >
            Back to Dashboard
        </button>

    </div>

</body>
</html>