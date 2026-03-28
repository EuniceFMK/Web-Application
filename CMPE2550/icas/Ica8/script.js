let currentst = null;
$(document).ready(() => {

    CallAjax("https://localhost:7226",
        "get",
        {},
        "json",
        loadStudents,
        ajaxError
    );
})

function loadStudents(response) {
    let tbody = $("tbody");
    tbody.empty();

    if (!response.students || response.students.length == 0) {
        tbody.append("<tr><td colspan='5'>No students found</td></tr>");
        return;
    }

    let data = response.students.slice(1); // skip headers
    data.forEach(st => {
        let row =
            ` <tr>
                <td>
                    <button onclick = "getStudents('${st[0]}')"> Retrieve</button>
                </td>
                <td>${st[0]}</td>
                <td>${st[1]}</td>
                <td>${st[2]}</td>
                <td>${st[3]}</td>
            </tr>`;
        tbody.append(row);

    });
    $("#retrieved").append(`Retrieved: ${response.students.length - 1} authors records.`);
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
    if (method == "get")
        ajaxOptions["data"] = data;
    if (method == "post") {
        ajaxOptions["data"] = JSON.stringify(data);
        ajaxOptions["contentType"] = "application/json";
    }
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

function getStudents(stid) {
    currentst = stid;
    console.log(parseInt(stid));
    CallAjax(`https://localhost:7226/StudentInfo?id=${stid}`,
        "GET",
        {
            id: parseInt(stid)
        },
        "json",
        loadStudentInfo,
        ajaxError
    );
}

function loadStudentInfo(response){
 let result = $("#stresult-body");
    let i = 0;
    $("#stresult").show();
    result.empty();
    console.log(response);
    if (!response.studentsInfo || response.studentsInfo.length == 0) {
        $("#stresult").hide();
        $("#outputres").empty();
        let out = $("<tr>");
        out.append($("<td>").text("No books found"));
        $("#outputres").append(out);
        return;

    }
    $("#outputres").empty();
    let data = response.studentsInfo.slice(1);
    data.forEach(st => {

        let row = $("<tr>").data('stid', st[0]);
        i = 0;
        console.log(`${st[0]}`);
        row.append($("<td>").text(st[0]));
        row.append($("<td>").text(st[1]));
        row.append($("<td>").text(st[2]));
        row.append($("<td>").text(st[3]));
        row.append($("<td>").text(st[4]));
        row.append($("<td>").text(st[5]));
         row.append($("<td>").text(st[6]));

        result.append(row);

    });

}