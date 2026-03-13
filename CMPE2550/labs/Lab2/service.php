<?php

require_once "db.php";

$output = array();
$clean = array();

// Sanitize and clean input parameters
function CleanCollection($input)
{
    global $connection;
    $cleanA = array();
    foreach ($input as $key => $value)
        if (is_array($value)) {
            $cleanA[trim($connection->real_escape_string(strip_tags(htmlspecialchars($key))))] = CleanCollection($value);
        } else {
            $cleanA[trim($connection->real_escape_string(strip_tags(htmlspecialchars($key))))]
                = trim($connection->real_escape_string(strip_tags(htmlspecialchars($value))));
        }

    return $cleanA;
}

$cleanGet = CleanCollection($_GET);
$cleanPost = CleanCollection($_POST);
error_log(json_encode($cleanGet));

$username = $cleanPost["username"];
$password = $cleanPost["password"];
$roleId = $cleanPost["roleId"] ?? 3; // Default to member role if not provided

// Handle actions based on the 'action' parameter
if (isset($cleanPost["action"])) {
    if ($cleanPost["action"] == "register")
        Register($cleanPost["username"], $cleanPost["password"]);

    if ($cleanPost["action"] == "login")
        Login($cleanPost["username"], $cleanPost["password"]);
    if ($cleanPost["action"] == "registerByRoot")
        RegisterByRoot($cleanPost["username"], $cleanPost["password"], $roleId);
    if($cleanPost["action"] == "updateRole")
        UpdateRole($cleanPost["userId"], $cleanPost["roleId"]);
    if($cleanPost["action"] == "DeleteUser")
        DeleteUser($cleanPost["userId"]);
}

if(isset($cleanGet["action"])){
    if($cleanGet["action"] == "getUsers")
        GetUsers();
}


echo (json_encode($output));
die();

function Register($username, $password)
{
    global $output;
    if (strlen($username) < 4) {
        $output["status"] = "Username too short";
        $output["valid"] = false;
        return;
    }
    $query = "SELECT*
              FROM Users 
              WHERE userName='$username'";

    $results = mySqlQuery($query);
    if ($results && $results->num_rows > 0) {
        $output["status"] = "Username already exists";
        $output["valid"] = false;
    } elseif (strlen($password) < 6) {
        $output["status"] = "Password too short";
        $output["valid"] = false;
    } elseif (!preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password)) {
        $output["status"] = "Password must contain at least one uppercase and one lowercase letter";
        $output["valid"] = false;
    } elseif (!preg_match("/[0-9]/", $password)) {
        $output["status"] = "Password must contain at least one number";
        $output["valid"] = false;
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertQuery = "INSERT INTO Users (userName, uPassword) VALUES ('$username', '$hashedPassword')";
        if (mySqlNonQuery($insertQuery) < 0) {
            $output["status"] = " Error during registration";
            $output["valid"] = false;
        } else {
            $output["status"] = "Registration successful";
            $output["valid"] = true;
            $rolequery = "INSERT INTO UsersRoles (userId, roleId) 
                          VALUES ((SELECT userId FROM Users WHERE userName='$username'), 3)";
            if (mySqlNonQuery($rolequery) < 0) {
                error_log("Error assigning role to user");
            }

        }
    }
}

function Login($username, $password)
{
    global $output;
    $query = "SELECT uPassword
              FROM Users 
              WHERE userName='$username'";

    $results = mySqlQuery($query);
    if ($results && $results->num_rows > 0) {
        $row = $results->fetch_assoc();
        if (password_verify($password, $row["uPassword"])) {
            $output["status"] = "Login successful";
            $output["valid"] = true;
        } else {
            $output["status"] = "Invalid password";
            $output["valid"] = false;
        }
    } else {
        $output["status"] = "Username not found";
        $output["valid"] = false;
    }

    if ($output["valid"]) {
        //session_start();

        $query2 = "SELECT roleId 
                  FROM UsersRoles ur 
                  JOIN Users u ON ur.userId = u.userId 
                  WHERE u.userName='$username'";

        $results = mySqlQuery($query2);
        if ($results && $results->num_rows > 0) {
            $roles = $results->fetch_assoc();
            $roleId = $roles["roleId"];
            if ($roleId == 3) {
                //$_SESSION["role"] = "member";
                $output["role"] = "member";
            } elseif ($roleId == 2) {
                //$_SESSION["role"] = "admin";
                $output["role"] = "admin";
            } elseif ($roleId == 1) {
                //$_SESSION["role"] = "root";
                $output["role"] = "root";
            }
            error_log("User role: " . $output["role"]);
        } else {
            error_log("Error fetching user roles");
        }
    }
}

function GetUsers(){
    global $output;
    $query = "SELECT u.userId, u.userName, u.uPassword, r.roleId
              FROM Users u
              JOIN UsersRoles ur ON u.userId = ur.userId
              JOIN Roles r ON ur.roleId = r.roleId";
    $results = mySqlQuery($query);
    if($results && $results->num_rows > 0){
        $output["users"] = $results->fetch_all();
    }
    else{
        $output["users"] = [];
    }
}

function RegisterByRoot($username, $password, $roleId)
{
    global $output;
    if (strlen($username) < 4) {
        $output["status"] = "Username too short";
        $output["valid"] = false;
        return;
    }
    $query = "SELECT*
              FROM Users 
              WHERE userName='$username'";

    $results = mySqlQuery($query);
    if ($results && $results->num_rows > 0) {
        $output["status"] = "Username already exists";
        $output["valid"] = false;
    } elseif (strlen($password) < 6) {
        $output["status"] = "Password too short";
        $output["valid"] = false;
    } elseif (!preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password)) {
        $output["status"] = "Password must contain at least one uppercase and one lowercase letter";
        $output["valid"] = false;
    } elseif (!preg_match("/[0-9]/", $password)) {
        $output["status"] = "Password must contain at least one number";
        $output["valid"] = false;
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertQuery = "INSERT INTO Users (userName, uPassword) VALUES ('$username', '$hashedPassword')";
        if (mySqlNonQuery($insertQuery) < 0) {
            $output["status"] = " Error during registration";
            $output["valid"] = false;
        } else {
            $output["status"] = "Registration successful";
            $output["valid"] = true;
            $rolequery = "INSERT INTO UsersRoles (userId, roleId) 
                          VALUES ((SELECT userId FROM Users WHERE userName='$username'), $roleId)";
            if (mySqlNonQuery($rolequery) < 0) {
                error_log("Error assigning role to user");
            }

        }
    }
}

function UpdateRole($userId, $roleId){
    global $output;
    $query = "UPDATE UsersRoles 
              SET roleId='$roleId' 
              WHERE userId='$userId'";
    if(mySqlNonQuery($query) < 0){
        $output["status"] = "Error updating user role";
        $output["valid"] = false;
    }
    else{
        $output["status"] = "User role updated successfully";
        $output["valid"] = true;
    }
}

function DeleteUser($userId){
    global $output;
    $query1 = "DELETE FROM UsersRoles WHERE userId='$userId'";
    $query2 = "DELETE FROM Users WHERE userId='$userId'";
    if(mySqlNonQuery($query1) < 0 || mySqlNonQuery($query2) < 0){
        $output["status"] = "Error deleting user";
        $output["valid"] = false;
    }
    else{
        $output["status"] = "User deleted successfully";
        $output["valid"] = true;
    }
}