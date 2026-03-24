// To handle on page load stuff
$().ready( ()=> {
    console.log("On page load");

    // On click binding for b1 button
    $("#b1").on("click", ()=>{
        console.log("B1 has been clicked");
        MakeAjaxCall("server.php", "GET", "HTML", {}, successB1, errorHandler);
    });

    // On click binding for b2 button
    $("#b2").on("click", ()=>{
        console.log("B2 has been clicked");
        let data = {};
        data['p1Name'] = "Harsimranjot";
        data['p2Name'] = "Dustin";
        data['action'] = "b2"; // To identify request on server side

        MakeAjaxCall("server.php", "GET", "HTML", data, successB2, errorHandler);
    });

    // On click binding for b2 button
    $("#b3").on("click", ()=>{
        console.log("B3 has been clicked");
        let data = {};
        data['p1Name'] = "Harsimranjot";
        data['p2Name'] = "Dustin";
        data['action'] = "b3"; // To identify request on server side

        MakeAjaxCall("server.php", "GET", "JSON", data, successB3, errorHandler);
    });
})

// function to handle successful response for B3 button
function successB3(serverData, serverStatus)
{
    console.log("Inside Success handler B3");
    console.log(serverData);
    // Target individual properties
    // Dot style
    console.log(serverData.name);
    // Array style
    console.log(serverData['name']);
    console.log(serverStatus);
    //$("#serverResponse").html(serverData);
}


// function to handle successful response for B2 button
function successB2(serverData, serverStatus)
{
    console.log("Inside Success handler");
    console.log(serverData);
    console.log(serverStatus);
    //$("#serverResponse").html(serverData);
}

// function to handle successful response for B1 button
function successB1(serverData, serverStatus)
{
    console.log("Inside Success handler");
    console.log(serverData);
    console.log(serverStatus);
    $("#serverResponse").html(serverData);
}

function errorHandler(ajaxReq, ajaxStatus, errorThrown)
{
    console.log("Inside error handler function");
    console.log(ajaxStatus);
    console.log(errorThrown);
    $("#serverResponse").html(ajaxStatus);
}

// MakeAjaxCall is used to make an ajax call
function MakeAjaxCall(serverURL, reqMethod, serverResponse, data, Successhandler, Errorhander)
{
    console.log("inside MakeAjaxCall function");

    let ajaxOptions= {};
    ajaxOptions['url']      = serverURL;    // Destination URL
    ajaxOptions['data']     = data;         // Client Data sent to server
    ajaxOptions['type']     = reqMethod;    // GET/POST
    ajaxOptions['dataType'] = serverResponse;// HTML/ JSON
    ajaxOptions['success']  = Successhandler;// Function to call on successful attemp
    ajaxOptions['error']    = Errorhander;   // Callback function in case of an error
    
    //Actually making ajax call now

    $.ajax(ajaxOptions);

}