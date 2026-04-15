$().ready(()=>{
    console.log("On page load");

    $("#postSubmit").click(PostSubmit);
    //https://localhost:7184/registerPost
});

function PostSubmit()
{
    console.log("Inside Post submit function");

    let data= {};
    data.postName= $("[name=postName]").val();
    data.postAge= $("[name=postAge]").val();

    console.log(data);
    
    MakeAjaxCall("https://aspdemo01a03-huekhtabedcyd8hz.canadacentral-01.azurewebsites.net/registerPost", "POST", "JSON", data, succssHandler, errorHandler)
    //MakeAjaxCall("https://localhost:7184/registerPost", "POST", "JSON", data, succssHandler, errorHandler)
}

function succssHandler(serverData, serverStatus)
{
    console.log(serverData);
    console.log(serverData.count);   // Object style
    console.log(serverData['name']); // Array style
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
    ajaxOptions['url']          = serverURL;            // Destination URL
    ajaxOptions['data']         = JSON.stringify(data); // Client Data sent to server-- NEW FOR ASP PART
    ajaxOptions['type']         = reqMethod;            // GET/POST
    ajaxOptions['dataType']     = serverResponse;       // HTML/ JSON
    ajaxOptions['success']      = Successhandler;       // Function to call on successful attemp
    ajaxOptions['error']        = Errorhander;          // Callback function in case of an error
    
    ajaxOptions['contentType']  = "application/json";   //NEW FOR ASP PART
    //Actually making ajax call now

    $.ajax(ajaxOptions);

}