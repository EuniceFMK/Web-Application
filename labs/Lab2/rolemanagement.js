$(document).ready(function () {
    loadRoles();
});

function loadRoles() {
    CallAjax(
        "service.php",
        "GET",
        {
            action: "getRoles"
        },
        "JSON",
        function (res) {

            let html = "";

            if (res.roles && res.roles.length > 0) {

                res.roles.forEach(function (r) {

                    html += `
                        <tr>
                            <td>${r[0]}</td>
                            <td>${r[1]}</td>
                            <td>${r[2]}</td>
                            <td>${r[3]}</td>
                            <td>
                                <button
                                    class="delete-btn"
                                    onclick="deleteRole(${r[0]})"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    `;
                });

            } else {
                html = `
                    <tr>
                        <td colspan="5">
                            No roles found
                        </td>
                    </tr>
                `;
            }

            $("#rolesTable").html(html);
        },
        ajaxError
    );
}

function addRole() {
    CallAjax("service.php", "POST", {
        action: "addRole",
        roleName: $("#roleName").val(),
        roleDesc: $("#roleDesc").val(),
        roleValue: $("#roleValue").val()
    }, "JSON", function (res) {
        alert(res.status);
        loadRoles();
    });
}

function deleteRole(id) {
    CallAjax("service.php", "POST", {
        action: "deleteRole",
        roleId: id
    }, "JSON", function (res) {
        alert(res.status);
        loadRoles();
    });
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