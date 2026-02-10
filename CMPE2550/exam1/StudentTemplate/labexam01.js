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