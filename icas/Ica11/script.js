let API = "https://localhost:7057";

$(document).ready(() => {

    loadLocations();

    $("#getOrdersBtn").click(getOrders);
      $("#placeOrderBtn").click(placeOrder);
});

/**
 *
 */
function CallAjax(url, method, data, dataType, successCallback, errorCallback) {
    let ajaxOptions = {};
    ajaxOptions["url"] = url;
    ajaxOptions["method"] = method;

    if (method == "get")
        ajaxOptions["data"] = data;

    if (method == "post"|| method == "put" || method == "delete") {
        ajaxOptions["data"] = JSON.stringify(data);
        ajaxOptions["contentType"] = "application/json";
    }

    ajaxOptions["dataType"] = dataType;
    ajaxOptions["success"] = successCallback;
    ajaxOptions["error"] = errorCallback;

    return $.ajax(ajaxOptions);
}

function ajaxError(req, status, err) {
    console.log("Ajax error", err);
}

/**
 * 🔹 Charger les locations
 */
function loadLocations() {

    CallAjax(API + "/RetLocation",
        "get",
        {},
        "json",
        function (response) {

            let options = `<option value="">Select Location</option>`;

            response.forEach(loc => {
                options += `<option value="${loc.location}">${loc.location}</option>`;
            });

            $("#location").html(options);
        },
        ajaxError
    );
}

function loadItems() {
    CallAjax(API + "/RetItems",
        "get",
        {},
        "json",
        function (response) {
            let options = `<option value="">Select Item</option>`;

            response.forEach(item => {
                options += `<option value="${item.itemId}">${item.itemName}</option>`;
            });

            $("#itemId").html(options);
        },
        ajaxError
    );
}
/**
 * Récupérer les commandes
 */
function getOrders() {

    let customerId = $("#customerId").val();
    let location = $("#location").val();

    // validation
    if (customerId === "") {
        alert("Customer ID is required!");
        $("#customerId").focus();
        return;
    }

    CallAjax(API + "/order",
        "post",
        {
            customerId: parseInt(customerId),
            location: location
        },
        "json",
        function (response) {

            let table = $("#ordersTable tbody");
            table.empty();

            if (response.length === 0) {
                $("#message").text("No records found.");
                $("#ordersTable").hide();
                return;
            }

            $("#message").text("");

            response.forEach(order => {
                let row = `
                    <tr>
                        <td>${order.orderId}</td>
                        <td>${order.item}</td>
                        <td>${order.quantity}</td>
                        <td>${order.payment}</td>
                        <td>${order.price}</td>
                        <td>
   <button onclick="deleteOrder(${order.orderId})">
      Delete
   </button>
</td>
                    </tr>
                `;
                table.append(row);
            });

            $("#ordersTable").show();
        },
        ajaxError
    );
}
function deleteOrder(id) {
    CallAjax(API + "/deleteOrder/" + id,
        "delete",
        {},
        "json",
        function (response) {
            alert(response.message);
            getOrders();
        },
        ajaxError
    );
}

function placeOrder() {
    let customerId = $("#newCustomerId").val();
    let itemId = $("#itemId").val();
    let quantity = $("#quantity").val();
    let payment = $("#payment").val();
    let locationId = $("#newLocation").val();

    if (customerId === "") {
        alert("Customer ID is required");
        $("#newCustomerId").focus();
        return;
    }

    CallAjax(API + "/placeOrder",
        "post",
        {
            customerId: parseInt(customerId),
            itemId: parseInt(itemId),
            quantity: parseInt(quantity),
            payment: payment,
            locationId: parseInt(locationId)
        },
        "json",
        function (response) {
            $("#orderConfirmation").html(
                `Order placed successfully!<br>
                 Order ID: ${response.orderId}<br>
                 Estimated Pickup: ${response.estimatedPickup}`
            );

            $("#placeOrderBtn").text("Update Order");
            $("#orderId").val(response.orderId);
        },
        ajaxError
    );
}