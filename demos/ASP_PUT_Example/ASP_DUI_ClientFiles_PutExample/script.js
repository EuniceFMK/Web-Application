$(document).ready(function() {


    $("#putOp").click(TestPUT);


});

function TestPUT() {
    console.log("In TestPUT");

    var putData = {};
    putData["cOld"] = "Germany";
    putData["cNew"] = "Canada";

    var options = {};
    options["method"] = "PUT";
    options["url"] = "https://localhost:7167/changeStuff";
    options["dataType"] = "json";
    options["data"] = JSON.stringify(putData);
    options["contentType"] = "application/json";
    options["success"] = successCallback;
    options["error"] = errorCallback;
    
    $.ajax(options);

};

function successCallback(returnedData) {

    console.log(returnedData);

    $("#output").html(returnedData["message"]);
};


function errorCallback(jqObject, returnedStatus, errorThrown) {
    console.log(jqObject);
    console.log(returnedStatus + " : " + errorThrown);


};