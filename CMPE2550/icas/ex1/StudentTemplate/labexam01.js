// Generic function as previously given for initiating AJAX requests
function AJAX(method, url, dataType, data, successCallback, errorCallback) {

    var options = {};
    options["method"] = method;
    options["url"] = url;
    options["dataType"] = dataType;
    options["data"] = data;
    options["success"] = successCallback;
    options["error"] = errorCallback;
    $.ajax(options);

};

// Shared error callback function
function Bad(d, s) {
    console.log(d);
    console.log(s);
}



// Add your code below this line to set up button listeners, AJAX request methods,
// success functions.

$(document).ready(function () {
    console.log("JS FILE LOADED");
    $("#clearSaved").click(clearsaved);    // Start a new game when "New Game" button is clicked
    $("#getSaved").click(getsaved);    // Start a new game when "New Game" button is clicked
    $("#submitNew").click(submitnew);    // Start a new game when "New Game" button is clicked
})

function submitnew() {
     console.log("Submit clicked"); 
    AJAX(
        "GET",
        "labexam01_service.php",
        "json",
        {
            action: "submitnew",
            seed: $("#seedNum").val()
        },
        function (response) {
            console.log(response);
        },
        Bad);
}
function getsaved() {
    AJAX(
        "GET",
        "labexam01_service.php",
        "json",
        {
            action: "getsaved",
            seed: $("#seedNum").val()
        },
        function (response) {
            $("#outputPartB").empty();
            if (response.response) {
                response.response.forEach(element => {
                    $("#outputPartB").append(element + ", ");
                });
            }
            else if (response.message) {
                $("#outputPartB").text(response.message);
            }
        },
        Bad);

}

function clearsaved() {
    AJAX(
        "GET",
        "labexam01_service.php",
        "json",
        {
            action: "clearsaved",
        },

        function (response) {
            console.log(response);
            console.log(response.response);

        },
        Bad);
}

