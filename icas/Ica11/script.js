let API = "https://localhost:7057";

$(document).ready(() => {

    loadLocations();
    loadItems();


    $("#getOrdersBtn").click(getOrders);
    $("#placeOrderBtn").click(function () {

        if ($(this).text() === "Place Order") {
            placeOrder();
        }
        else {
            updateOrder();
        }
    });
});

/**
 *
 */
function CallAjax(url, method, data, dataType, successCallback, errorCallback) {
    let ajaxOptions = {};
    ajaxOptions["url"] = url;
    ajaxOptions["method"] = method;

    if (method.toLowerCase() === "get") {
        ajaxOptions.data = data;
    }
    else {
        ajaxOptions.data = JSON.stringify(data);
        ajaxOptions.contentType = "application/json; charset=utf-8";
    }

    ajaxOptions["dataType"] = dataType;
    ajaxOptions["success"] = successCallback;
    ajaxOptions["error"] = errorCallback;

    return $.ajax(ajaxOptions);
}

function ajaxError(req, status, err) {
    console.log("STATUS:", status);
    console.log("ERROR:", err);
    console.log("RESPONSE:", req.responseText);
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
                options += `
                    <option value="${loc.locationId}">
                        ${loc.locationName}
                    </option>
                `;
            });

            $("#location").html(options);
            $("#newLocation").html(options);
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
        "DELETE",
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

    console.log({
        customerId,
        itemId,
        quantity,
        payment,
        locationId
    });
    if (customerId === "") {
        alert("Customer ID is required");
        return;
    }

    if (itemId === "") {
        alert("Please select an item");
        return;
    }

    if (locationId === "") {
        alert("Please select a pickup location");
        return;
    }

    if (quantity === "" || parseInt(quantity) <= 0) {
        alert("Quantity must be greater than 0");
        return;
    }

    if (payment.trim() === "") {
        alert("Payment method is required");
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

            $("#newCustomerId").prop("disabled", true);
            $("#newLocation").prop("disabled", true);
            $("#orderId").prop("disabled", true);
        },
        ajaxError
    );
}
function updateOrder() {

    let orderId = parseInt($("#orderId").val());
    let itemId = parseInt($("#itemId").val());
    let quantity = parseInt($("#quantity").val());
    let payment = $("#payment").val();

    console.log("UPDATE REQUEST SENT:", JSON.stringify({
        orderId,
        itemId,
        quantity,
        payment
    }));
    if (!orderId || orderId <= 0) {
        alert("Order ID is missing. Please place an order first.");
        return;
    }

    if (!itemId) {
        alert("Please select item");
        return;
    }

    if (!quantity || quantity <= 0) {
        alert("Quantity must be greater than 0");
        return;
    }

    if (!payment || payment.trim() === "") {
        alert("Payment method is required");
        return;
    }

    CallAjax(API + "/updateOrder",
        "PUT",
        {
            orderId: orderId,
            itemId: itemId,
            quantity: quantity,
            payment: payment
        },
        "json",
        function (response) {
            $("#orderConfirmation").html(response.message);
        },
        function (req, status, err) {
            console.log("SERVER RESPONSE:", req.responseText);
            console.log("STATUS:", status);
            console.log("ERROR:", err);
        }
    );
}
//Scaffold-DbContext "Server=data.cnt.sast.ca,24680;Database=efmukamngadjou1_RestaurantDB;User Id=efmukamngadjou1;Password=Rachel1980@.;Encrypt=False;" Microsoft.EntityFrameworkCore.SqlServer -OutputDir Models