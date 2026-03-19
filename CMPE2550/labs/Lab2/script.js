$(document).ready(function () {
    $("#message").hide();
    $("#regButton").click(Registration);
    $("#loginButton").click(Login);
});

/**
 * FunctionName:    CallAjax
 * Inputs:          url - URL to send the AJAX request to
 *                  method - HTTP method (GET, POST, etc.)
 *                  data - Data to be sent with the request 
 *                  dataType - Expected data type of the response
 *                  successCallback - Function to call on successful response
 *                  errorCallback - Function to call on error response
 * Outputs:         AJAX request object
 * Decription:     Sends an AJAX request with the specified parameters.
 */
function CallAjax(url, method, data, dataType, successCallback, errorCallback) {
    let ajaxOptions = {};
    ajaxOptions["url"] = url;
    ajaxOptions["method"] = method;
    ajaxOptions["data"] = data;
    ajaxOptions["dataType"] = dataType;
    ajaxOptions["success"] = successCallback;
    ajaxOptions["error"] = errorCallback;
    console.log(ajaxOptions);
    return $.ajax(ajaxOptions);
}

/**
 * FunctionName:    ajaxError
 * Inputs:          err - Error object from the AJAX request
 * Outputs:        None
 * Decription:     Logs an error message to the console.
 */
function ajaxError(req, status, err) {
    console.log("Ajax error", err);
    console.log(status);
    console.log(req.status);
}

function Registration() {
    if (username.length < 4) {
        alert("Username must be at least 4 characters");
        return;
    }

    if (password.length < 6) {
        alert("Password must be at least 6 characters");
        return;
    }

    CallAjax("service.php",
        "POST",
        {
            action: "register",
            username: $("#username").val(),
            password: $("#password").val()
        },
        "JSON",
        successRegister,
        ajaxError);
}

function successRegister(response) {
    //console.log("Registration successful");
    $("#message").show();
    $("#message").text(response.status);
    console.log(response.status);
}

function Login() {
    CallAjax("service.php",
        "POST",
        {
            action: "login",
            username: $("#username").val(),
            password: $("#password").val()
        },
        "JSON",
        successLogin,
        ajaxError);
}

function successLogin(response) {
    console.log("Login response:", response);
    $("#message").hide();
    if (response.status == "Login successful") {

        if (response.role == "admin") {
            window.location.href = "userManagement.php";
        }
        else if (response.role == "root") {
            window.location.href = "userManagement.php";
        }
        else if (response.role == "member") {
            window.location.href = "user.php";
        }
    } else {
        $("#message").show();
        $("#message").text(response.status);
    }
}