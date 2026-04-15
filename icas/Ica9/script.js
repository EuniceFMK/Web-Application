let currentst = null;
$(document).ready(() => {
    $("#stresult").hide();
$("#outputres").hide();
    CallAjax("https://localhost:7226",
        "get",
        {},
        "json",
        loadStudents,
        ajaxError
    );


    CallAjax("https://localhost:7226/ClassIds",
        "GET",
        {},
        "json",
        function (response) {
            let cID = $("#cID");

            response.classInfo.forEach(classId => {
                let opt = $("<option>").val(classId[0]).text(classId[0]);
                cID.append(opt);
            });
        },
        ajaxError
    )
})

function loadStudents(response) {
    let tbody = $("#mainbody");
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
                    <button onclick = "getStudents('${st[0]}')"> Retrieve Class Info</button>
                </td>
                <td class = "id" >${st[0]}</td>
                <td class = "fn">${st[2]}</td>
                <td class = "ln">${st[1]}</td>
                <td class = "ids">${st[3]}</td>
                 <td class='action'>
                    <button onclick = "Delete('${st[0]}')"> Delete</button>
                    <button onclick = "Edit(this,'${st[0]}')"> Edit</button>
                </td>
            </tr>`;
        tbody.append(row);

    });
    $("#retrieved").html(`Retrieved: ${response.students.length - 1} authors records.`);
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
    if (method == "POST" || method == "PUT") {
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

function loadStudentInfo(response) {

    let result = $("#stresult-body");
    let i = 0;
    $("#stresult").show();
    result.empty();
    console.log(response);
    // if (!response.studentsInfo || response.studentsInfo.length == 0) {
    //     $("#stresult").hide();
    //     $("#outputres").empty();
    //     let out = $("<tr>");
    //     out.append($("<td>").text("No books found"));
    //     $("#outputres").append(out);
    //     return;

    // }
    $("#outputres").empty();
    let data = response.studentsInfo.slice(1);
    data.forEach(st => {
        let mess = `Retrieved information for student with id ${st[0]}`
        $("#stinfo").empty();
        $("#stinfo").append(mess);

        let row = $("<tr>").data('stid', st[0]);
        i = 0;
        console.log(`${st[0]}`);
        row.append($("<td>").text(st[0]));
        row.append($("<td>").text(st[1]));
        row.append($("<td>").text(st[2]));
        row.append($("<td>").text(st[3]));
        row.append($("<td>").text(st[4]));
        row.append($("<td>").text(st[6]));
        row.append($("<td>").text(st[5]));

        result.append(row);

    });

}

function Delete(stid) {
    currentst = stid;
    console.log(parseInt(stid));

    CallAjax(`https://localhost:7226/remove/${stid}`,
        "DELETE",
        {
            id: parseInt(stid)
        },
        "json",
        function (response) {
            CallAjax("https://localhost:7226",
                "get",
                {},
                "json",
                loadStudents,
                ajaxError
            );
        },
        ajaxError
    );
}
function Edit(btn, stid) {
    //let btn = $(this);
    currentst = stid;
    let row = $(btn).closest("tr");
    let action = row.find(".action");
    let firstname = row.find(".fn");
    let lastname = row.find(".ln");
    let schoolid = row.find(".ids");
    action.data("oldContent", action.html());
    firstname.data("oldContent", firstname.text());
    lastname.data("oldContent", lastname.html());
    schoolid.data("oldContent", schoolid.html());
    action.html(`
        <button class="update">Update</button>
        <button class="cancel">Cancel</button>
    `);
    firstname.html(`<input type='text' class='titleinput' value='${firstname.data("oldContent")}'>`);
    lastname.html(`<input type='text' class='priceinput' value='${lastname.data("oldContent")}'>`);
    schoolid.html(`<input type='text' class='priceinput' value='${schoolid.data("oldContent")}'>`);
    console.log("in edit");
}


$(document).on("click", ".cancel", function () {
    let btn = $(this);
    let row = btn.closest("tr");

    let action = row.find(".action");
    let firstname = row.find(".fn");
    let lastname = row.find(".ln");
    let schoolid = row.find(".ids");

    // Restore old values
    action.html(action.data("oldContent"));
    firstname.text(firstname.data("oldContent"));
    lastname.text(lastname.data("oldContent"));
    schoolid.html(schoolid.data("oldContent"));

});

//Update button click event handler
$(document).on("click", ".update", function () {
    let btn = $(this);
    let row = btn.closest("tr");
    let newfname = row.find(".fn input").val();
    let newlname = row.find(".ln input").val();
    let newsid = row.find(".ids input").val();
    // if (isNaN(newsid) || newsid.trim() === "") {
    //     $("#retrieved").text("School ID must be a valid number");
    //     return;
    // }
    CallAjax(`https://localhost:7226/update/${currentst}`,
        "PUT",
        {
            id: parseInt(currentst),
            fname: newfname,
            lname: newlname,
            shid: newsid
        },
        "json",
        function (response) {
            $("#outputres").html(`${response.message}`);
            $("#outputres").show();
            CallAjax("https://localhost:7226",
                "get",
                {},
                "json",
                loadStudents,
                ajaxError
            );
        },
        ajaxError
    );

})

$(document).on("click", "#addStudentBtn", function () {
    let btn = $(this);
    let row = btn.closest("tr");
    let newfname = $("#fnInput").val();
    let newlname = $("#lnInput").val();
    let newsid = $("#shId").val();
    let classid = $("#cID").val();
    CallAjax(`https://localhost:7226/add`,
        "POST",
        {
            fname: newfname,
            lname: newlname,
            shid: parseInt(newsid),
            classid: classid
        },
        "json",
        function (response) {
            CallAjax("https://localhost:7226",
                "get",
                {},
                "json",
                loadStudents,
                ajaxError
            );
        },
        ajaxError
    );

})
