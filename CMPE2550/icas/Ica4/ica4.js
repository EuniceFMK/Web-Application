/**
 * Programmer Block
 *   Name: Eunice Fmukam Ngadjou
 *  Date: January 30, 2026
 *  Assignment: ICA 4 - Author and Book Management
 *  Description: JavaScript code to handle AJAX requests and DOM manipulation for ICA 4.
 */

let currentauth = null;
let j = 0;
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
function ajaxError(req, status, err) {
    console.log("Ajax error", err);
    console.log(status);
    console.log(req.status);
}

/**
 * FunctionName:    getBooks
 * Inputs:          authid - Author ID to retrieve books for
 * Outputs:        None
 * Decription:     Retrieves and displays books for a specific author.
 */
function getBooks(authid) {
    currentauth = authid;
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
 * FunctionName:    getBooks
 * Inputs:          authid - Author ID to retrieve books for
 * Outputs:        None
 * Decription:     Retrieves and displays books for a specific author.
 */
function Deletebook(titleID, au_id) {
    CallAjax("service.php",
        "GET",
        {
            action: "DeleteBooksByAuthors",
            titleID: titleID
        },
        "json",
        function (response) {
            if (response)
                getBooks(au_id);
            console.log(response);
        },
        ajaxError
    );
}


/**
 * FunctionName:    Editbook
 * Inputs:          titleID - ID of the book to be edited
 * Outputs:        None
 * Decription:     Sends an AJAX request to edit a book and refreshes the book list on success.
 */
function Editbook(titleID) {
    CallAjax("service.php",
        "GET",
        {
            action: "EditBook",
            titleID: titleID
        },
        "json",
        function (response) {
            if (response)
                getBooks(au_id);
            console.log(response);
        },
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
    let i = 0;
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

        let row = $("<tr>").data('titleid', author[0]);
        i = 0;
        console.log(`${author[0]}`);
        row.append($("<td class='action-cell'>")
            .append($(`<button class="delete" onclick = "Deletebook('${author[0]}','${currentauth}')">`).text("Delete"))
            .append($(`<button class='edit'>`).text("Edit")));
        row.append($("<td>").text(author[0]));
        row.append($("<td>").addClass("title-cell").text(author[1]));
        row.append($("<td>").addClass("type-cell").text(author[2]));
        row.append($("<td>").addClass("price-cell").text(author[3]));

        book.append(row);

    });
    j = i;
    let num = $("<p>")
    num.addClass("count-row");
    num.text(`Retrieved: ${response.books.length} book records`);

    book.append(num);
}


//Edit button click event handler
$(document).on("click", ".edit", function () {
    let btn = $(this);
    CallAjax("service.php",
        "GET",
        {
            action: "GetAllTypes",
        },
        "json",
        function (response) {
            EditCallBack(response, btn);
        },
        ajaxError
    );

});


/**
 * FunctionName:    EditCallBack
 * Inputs:          response - Response data from the AJAX request
 *                  btn - The edit button that was clicked
 * Outputs:        None
 * Decription:     Transforms the book row into an editable form with input fields and a dropdown for book types, allowing the user to update or cancel their changes.
 */
function EditCallBack(response, btn) {
    let row = btn.closest("tr");
    let typeCell = row.find(".type-cell");
    let actionCell = row.find(".action-cell");
    let titleCell = row.find(".title-cell");
    let priceCell = row.find(".price-cell");
    actionCell.data("oldContent", actionCell.html());
    titleCell.data("oldContent", titleCell.text());
    priceCell.data("oldContent", priceCell.text());
    actionCell.html(`
        <button class="update">Update</button>
        <button class="cancel">Cancel</button>
    `);
    titleCell.html(`<input type='text' class='titleinput' value='${titleCell.data("oldContent")}'>`);
    priceCell.html(`<input type='text' class='priceinput' value='${priceCell.data("oldContent")}'>`);
    let dropList = $("<select>");
    response.types.forEach(type => {
        let opt = $("<option>").val(type[0]).text(type[0]);
        if(type[0] == typeCell.data("oldContent")){
            typeCell.data("oldContent", type[0]);
            opt.prop("selected", true);
        }
        dropList.append(opt);
    });
    typeCell.empty();
    typeCell.append(dropList);
    console.log(response);

}

//Update button click event handler
$(document).on("click", ".update", function () {
    let btn = $(this);
    let row = btn.closest("tr");
    let newtype = row.find("select").val();
    let newtitle = row.find(".titleinput").val();
    let newprice = row.find(".priceinput").val();
    if(isNaN(newprice) || newprice.trim() === ""){
        alert("Please enter a valid number for price.");
        return;
    }else{
    CallAjax("service.php",
        "GET",
        {
            action: "EditBook",
            title:newtitle,
            type:newtype,
            price:newprice,
            titleID: row.data("titleid")
        },
        "json",
        function (response) {
            if (response) {
                getBooks(currentauth);
            }
        },
        ajaxError
    );
}
})

//Cancel button event handler
$(document).on("click", ".cancel", function () {
    let btn = $(this);
    let row = btn.closest("tr");

    let actionCell = row.find(".action-cell");
    let titleCell = row.find(".title-cell");
    let priceCell = row.find(".price-cell");
    let typeCell  = row.find(".type-cell");

    // Restore old values
    actionCell.html(actionCell.data("oldContent"));
    titleCell.text(titleCell.data("oldContent"));
    priceCell.text(priceCell.data("oldContent"));
    typeCell.text(typeCell.data("oldContent"));
});
