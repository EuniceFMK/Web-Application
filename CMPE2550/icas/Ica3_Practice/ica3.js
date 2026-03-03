/*Progranmer:   Ngadjou Eunice Fmukam
 *Date:         January 30, 2026
 *FileName:     ica3.js
 *Description:  JavaScript file to handle AJAX requests and DOM manipulation for ICA 3.
*/

$(document).ready(function () {
    // Load all authors when the document is ready
    CallAjax("service.php",
        "GET",
        {
            action: "GetAllAuthor"
        },
        "JSON",
        success,
        ajaxError);

})

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
function ajaxError(err) {
    console.log("Ajax error", err);
}

function success(response) {
    let books = $("tbody");
    books.empty();
    console.log(response.authors);
     if (!response.authors || response.authors.length == 0) {
        books.append("<tr><td colspan='5'>No authors found</td></tr>");
        return;
    }
    
    response.authors.forEach(element => {
       let row = ` <tr>
                   <td>
                   <button>Retreive</button>
                   </td>
                   <td>${element[0]}</td>
                   <td>${element[1]}</td>
                   <td>${element[2]}</td>
                   <td>${element[3]}</td>
               `;
        books.append(row);
    
    });
}