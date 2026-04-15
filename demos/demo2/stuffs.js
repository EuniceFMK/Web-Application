
$(document).ready(function () {
    $("#submit").click();

    function GetVolume() {
        let getData = {};
        getData["action"] = "CalcVol";
        getData["radius"] = $("#radius").val();
        console.log(getData);
        CallAjax("service.php", "get", getData, "json", calcVolsuccess, ErrorMethod);
    }
})
function CallAjax(url, method, data, dataType, successCallback, errorCallback) {
    let ajaxOptions = {};
    ajaxOptions["url"] = url;
    ajaxOptions["method"] = method;
    ajaxOptions["data"] = data;
    ajaxOptions["dataType"] = dataType;
    ajaxOptions["success"] = successCallback;
    ajaxOptions["error"] = errorCallback;
    return $.ajax(ajaxOptions);

}



function AreaVolumeSuccess(returnedData, returnedStatus, sentRequest) {
    console.log(returnedData);
    console.log(returnedStatus);
    console.log(sentRequest);
}

function ErrorMethod(returnedData, returnedStatus, sentRequest) {
    console.log(returnedData);
    console.log(returnedStatus);
    console.log(sentRequest);
}