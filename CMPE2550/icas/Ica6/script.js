let allMenus = {};
$(document).ready(() => {

    CallAjax("https://localhost:7057/welcome",
        "get",
        {
            message: "Welcome to Tim Hortons"
        },
        "json",
        function (response) {
            $("#header").html(response.output);
        },
        ajaxError
    );

    Locations();
    $("#location").change(function () {
        let selectedLocation = $(this).val();
        updateMenu(selectedLocation);
    })
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

function Locations() {

    CallAjax("https://localhost:7057/data",
        "post",
        {},
        "json",
        LocationCallBack,
        ajaxError
    )
}

function LocationCallBack(response) {

    console.log(response);
    allMenus = response.menus;
    let locPlace = "";

    response.locations.forEach(function (loc) {
        locPlace += `<option value="${loc}">${loc}</option>`;
    })

    $("#location").html(locPlace);

    updateMenu(response.locations[0]);
}

function updateMenu(location) {
    let menu = allMenus[location];

    let menuPlace = "";
    let optionsPlace = "";

    menu.forEach(function (item) {
        menuPlace += `<li>${item}</li>`;
        optionsPlace += `<option value="${item}">${item}</option>`;
    })

    $("#menuList").html(menuPlace);
    $("#items").html(optionsPlace);
}