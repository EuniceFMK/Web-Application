$(document).ready(function () {
    loadRoles();
});

function loadRoles() {
    CallAjax("service.php", "GET", { action: "getRoles" }, "JSON", function (res) {
        let html = "";

        res.roles.forEach(r => {
            html += `
            <tr>
                <td>${r[0]}</td>
                <td>${r[1]}</td>
                <td>${r[3]}</td>
                <td>
                    <button onclick="deleteRole(${r[0]})">Delete</button>
                </td>
            </tr>`;
        });

        $("#rolesTable").html(html);
    });
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