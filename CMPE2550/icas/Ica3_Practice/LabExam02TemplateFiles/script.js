// Remember:  Do NOT modify this template file except where directed to do so

// Note: Examine the HTML in the index.php file and the JS below to see what is being sent to the server


function AJAX(method, url, dataType, data, successCallback, errorCallback) {

    var options = {};
    options["method"] = method;
    options["url"] = url;
    options["dataType"] = dataType;
    options["data"] = data;
    options["success"] = successCallback;
    options["error"] = errorCallback;
    $.ajax(options);
    console.log(options);
};

var ajaxURL = "service.php";
$().ready(function() {

    $("#btnPartA").click(function(e) {
        var getData = {};
        getData["partA"] = $("#tagFilter").val();
        getData["action"] = "Gettags"
        AJAX("GET", ajaxURL, "json", getData, successPartA, Bad);
    });

    $("#btnPartB").click(function(e) {
        var getData = {};
        getData["partB"] = $("#tagIDFilter").val();
        getData["action"] ="GettagsbyId";
        AJAX("POST", ajaxURL, "json", getData, successPartB, Bad);
    });

});

// Success callback functions
function successPartA(returnedData) {
    console.log(returnedData);
    $message = $``
    $("#divPartA").html(`There are ${returnedData.tags.length} with tagMin+tagMax sum less than ${$("#tagFilter").val()}: THE aVERAGE DIFFERENCE BETWEEN TAGmIN AND TAGax is ${returnedData.tagsdiff}`);
    
}

function successPartB(returnedData) {
    console.log(returnedData);   
  
    console.log(returnedData.tagsId);  
    let tbody = $("#tbodyPartB");
    tbody.empty();
    if(!returnedData.tagsId||returnedData.tagsId.length==0){
        tbody.append("<tr><td colspan='5'>No tag found</td></tr>");
        return;
    }

    returnedData.tagsId.forEach(tag =>{
        let row = $("<tr>");
        row.append($("<td>").text(tag[0]));
        row.append($("<td>").text(tag[1]));
        row.append($("<td>").text(tag[2]));
        // let max = Number.ParseInt(tag[0]);
        //  let min = Number.ParseInt(tag[1]);
        // let s=(max-min)*(max-min);
        // row.append($("<td>").text(s));
        tbody.append(row);
    })
    // Your table must be completed here.  Remember to examine the HTML so you know what to add in.  CSS has been completed for you.
}


// Shared error callback function
function Bad(d, s) {
    console.log(d);
    console.log(s);
}