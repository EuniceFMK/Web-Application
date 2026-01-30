

$(document).ready(function () {
    CallAjax("service.php",
        "GET",
        { action: "GetAllAuthor" },
        "json",
        loadAuthors,
        ajaxError
    );
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
    console.log(ajaxOptions[data]);
    return $.ajax(ajaxOptions);
}

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
                    <button onclick = "getBooks('${author[0]}')"> Get Books </button>
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

function ajaxError(err){
    console.log("Ajax error",err);
}

function getBooks(authid){
    CallAjax( "service.php",
        "GET",
        {action :"GetBooksByAuthors",
         au_id  :  authid
        },
        "json",
        loadBooks,
        ajaxError
    );
}

function loadBooks(response){
    let books = $("bookresult")
    books.empty();

    if(!response.books || response.books.length == 0) {
        tbody.append("<tr><td colspan='5'>No books found</td></tr>");
        return;
    }
     response.books.forEach(author => {
        let row =
           ` <tr>
                <td>
                    <button onclick = "getBooks('${author[0]}')"> Get Books </button>
                </td>
                <td>${author[0]}</td>
                <td>${author[1]}</td>
                <td>${author[2]}</td>
                <td>${author[3]}</td>
            </tr>`;
            tbody.append(row);
        
    });
    tbody.append(`Retrieved: ${response.books.length} authors records.`);
}