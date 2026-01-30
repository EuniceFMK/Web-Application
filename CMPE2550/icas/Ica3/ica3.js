/*Progranmer:   Ngadjou Eunice Fmukam
 *Date:         January 30, 2026
 *FileName:     ica3.js
 *Description:  JavaScript file to handle AJAX requests and DOM manipulation for ICA 3.
*/

$(document).ready(function () {

    // Load all authors when the document is ready
    CallAjax("service.php",
        "GET",
        { action: "GetAllAuthor" },
        "json",
        loadAuthors,
        ajaxError
    );
    $("#bookresult").hide();  // Hide book results section initially
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
 * FunctionName:    loadAuthors
 * Inputs:          response - Response data from the AJAX request
 * Outputs:        None
 * Decription:     Populates the authors table with data from the AJAX response.
 */
function loadAuthors(response) {
    let tbody = $("tbody");
    tbody.empty();

    if (!response.authors || response.authors.length == 0) {
        tbody.append("<tr><td colspan='5'>No authors found</td></tr>");
        return;
    }

    response.authors.forEach(author => {
        let row =
            ` <tr>
                <td>
                    <button onclick = "getBooks('${author[0]}')"> Retrieve  </button>
                </td>
                <td>${author[0]}</td>
                <td>${author[1]}</td>
                <td>${author[2]}</td>
                <td>${author[3]}</td>
            </tr>`;
        tbody.append(row);

    });
    tbody.append(`Retrieved: ${response.authors.length} authors records.`);
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

/**
 * FunctionName:    getBooks
 * Inputs:          authid - Author ID to retrieve books for
 * Outputs:        None
 * Decription:     Retrieves and displays books for a specific author.
 */
function getBooks(authid) {
    CallAjax("service.php",
        "GET",
        {
            action: "GetBooksByAuthors",
            au_id: authid
        },
        "json",
        loadBooks,
        ajaxError
    );
}

/**
 * FunctionName:    loadBooks
 * Inputs:          response - Response data from the AJAX request
 * Outputs:        None
 * Decription:     Populates the books table with data from the AJAX response.
 */
function loadBooks(response) {
    let book = $("#books-body");
    $("#bookresult").show();
    book.empty();
    console.log(response);
    if (!response.books || response.books.length == 0) {
        $("#bookresult").hide();
        $("#outputres").empty();
        let out = $("<tr>");
        out.append($("<td>").text("No books found"));
        $("#outputres").append(out);
        return;

    }
   $("#outputres").empty();
    response.books.forEach(author => {

        let row = $("<tr>");
        row.append($("<td>").text(author[0]));
        row.append($("<td>").text(author[1]));
        row.append($("<td>").text(author[2]));
        row.append($("<td>").text(author[3]));

        book.append(row);

    });
    let num=$("<tr>")
    num.addClass("count-row");
    num.append($("<td>").text(`Retrieved: ${response.books.length} book records`))
    
    book.append(num);
}