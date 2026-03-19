$(document).ready(function () {
    getUsers();
    $("#addUserBtn").click(Registration);
});

function getUsers() {
        CallAjax("service.php",
        "GET",
        {
            action: "getUsers"
        },
        "json",
        getUsersSuccess,
        ajaxError
    );
}

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

function getUsersSuccess(response) {
    console.log(response);
    let $tableBody = $("#usersTableBody");
    $tableBody.empty();
    if (response.users && response.users.length > 0) {
        response.users.forEach(function (user) {
            let $row = $("<tr>");
            let $role=user[3];  
            // let $div = $("<div>");
            // $div.append($("<button type='button'>").text("Update")
            //             .attr("id", "update_" + user[0])
            //             .click(Update));
            // $div.append($("<button type='button'>").text("Delete")
            //             .attr("id", "delete_" + user[0])
            //             .click(Delete));
            $row.append($("<td>")
                        .append($(`<button type="button" id="update_${user[0]}" onclick="Update(this)">`).text("Update"))
                        .append(($(`<button type='button' id="delete_${user[0]}" onclick="Delete(this)">`).text("Delete")
                       )));
            $row.append($("<td>").text(user[0]));
            $row.append($("<td>").text(user[1]));
            $row.append($("<td>").text(user[2]));
            $row.append($("<td>").append(
                $("<select>")
                    .attr("id", "role_" + user[0])
                    .append($("<option>").val("1").text("Root"))
                    .append($("<option>").val("2").text("Admin"))
                    .append($("<option>").val("3").text("Member"))
                    .val($role)
            ));
            $tableBody.append($row);
        });
    }
}


function Registration() {
    username = $("#username").val();
    password = $("#password").val();
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
            action: "registerByRoot",
            username: $("#username").val(),
            password: $("#password").val(),
            roleId: $("#roles").val()
        },
        "JSON",
        successRegister,
        ajaxError);
}

function successRegister(response) {
    CallAjax("service.php",
        "GET",
        {
            action: "getUsers"
        },
        "json",
        getUsersSuccess,
        ajaxError
    );
    //console.log("Registration successful");
    $("#message").show();
    $("#message").text(response.status);
    console.log(response.status);

}

function Update(btn) {
    let userId = $(btn).attr("id").split("_")[1];
    let newRoleId = $("#role_" + userId).val();

    CallAjax("service.php",
        "POST",
        {
            action: "updateRole",
            userId: userId,
            roleId: newRoleId
        },
        "JSON",
        function (response) {
            $("#message").show();
            $("#message").text(response.status);
            getUsers();
        },
        ajaxError);
}

function Delete(btn) {
    let userId = $(btn).attr("id").split("_")[1];

    CallAjax("service.php",
        "POST",
        {
            action: "DeleteUser",
            userId: userId
        },
        "JSON",
        function (response) {
            $("#message").show();
            $("#message").text(response.status);
            getUsers();
        },
        ajaxError);
}
//  .attr("id", "delete_" + user[0])
//                         .click(Delete))