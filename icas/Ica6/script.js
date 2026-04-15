let allMenus = {};
let item
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
         if ($("#location").val() == "loc") {
          $("#menuList").empty();
           $("#items").empty();
        }
        
    })

    $("#items").change(function () {
        item = $("#items").val();
        console.log(item);
    })

    $("#orderButton").click(Order);
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
    locPlace += `<option value="loc">Choose your location</option>`;
    response.locations.forEach(function (loc) {
        locPlace += `<option value="${loc}">${loc}</option>`;
    })

    $("#location").html(locPlace);

    if ($("#location").val() == "loc") {
        $("#menuList").empty();
    }


}

function updateMenu(location) {

        console.log(location);
        $("#menuList").empty();
        if(location=="loc")
            return;
        let menu = allMenus[location];

        let menuPlace = "";
        let optionsPlace = "";
        optionsPlace += `<option value="select">Select your item from the menu</option>`;
        menu.forEach(function (item) {
            menuPlace += `<li>${item}</li>`;
            optionsPlace += `<option value="${item}">${item}</option>`;
        })

        $("#menuList").append("<h1>Select your item from the menu list</h1>")
        $("#menuList").append(menuPlace);
        $("#items").html(optionsPlace);
        item = $("#items").val();
    

    console.log(item);
}

function Order() {

    if($("#location").val() == "loc"){
         let div = `<div class="order-summary successfailed"> 
                   Please select a pickup location<br>  
               </div>`

                $("#output").html(div);
                return;
    }
        
    let qty = parseInt($("#quantity").val());
    let payment = $("#payment").val();

    $("#items").change(function () {
        item = $("#items").val();
        console.log(item);
    })

    if (isNaN(qty)) {
        $("#output").text("Please enter a valid quantity");
        return;
    }

    CallAjax("https://localhost:7057/order",
        "post",
        {
            name: $("#name").val(),
            item: item,
            quantity: qty,
            payment: payment
        },
        "json",
        function (response) {
            console.log("In order");
            console.log(response.output);
            console.log(response.paiement);

            if (response.valid) {
                let div = `<div class="order-summary">
               <h2>Order Summary</h2>

               <div class="row">
                   <span class="label">Name:</span>
                   <span class="value">${$("#name").val()}</span>
                   <br> 
                   <span class="label">Location:</span>
                   <span class="value">${$("#location").val()}</span>
                   <br> 
                   <span class="label">Item:</span>
                   <span class="value">${item}</span> <br>
                   <span class="label">Quantity:</span>
                   <span class="value">${qty}</span> <br>
                    <span class="label">Cost:</span>
                   <span class="value">${response.paiement}</span> <br>    
                   <span class="label">Payment:</span>
                   <span class="value">${payment}</span> <br>          
               </div>
               <div class="success">
                   ${response.output}
                   <p>Your order will be ready for pickup in ${response.time} minutes </p>
               </div>
           </div>`;
                $("#output").html(div);
            }
            else {
                let div = `<div class="order-summary successfailed"> 
                   ${response.paiement}<br>  
                   ${response.output}
               </div>`

                $("#output").html(div);
            }



        },
        ajaxError
    )


}

