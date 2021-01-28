// Pour obtenir une présentation du modèle Vide, consultez la documentation suivante :
// http://go.microsoft.com/fwlink/?LinkID=397704
// Pour déboguer du code durant le chargement d'une page dans cordova-simulate ou sur les appareils/émulateurs Android, lancez votre application, définissez des points d'arrêt, 
// puis exécutez "window.location.reload()" dans la console JavaScript.

//var apiURL = 'http://localhost/layover/api.php';
//var fileUploaderURL = 'http://localhost/layover/fileUpload.php';

var apiURL = 'http://cheyennevalmond.com/layover/api.php';
var fileUploaderURL = 'http://cheyennevalmond.com/layover/fileUpload.php';

var usrData = null;

(function () {
    "use strict";

    document.addEventListener( 'deviceready', onDeviceReady.bind( this ), false );

    function onDeviceReady() {
        // Gérer les événements de suspension et de reprise Cordova
        document.addEventListener( 'pause', onPause.bind( this ), false );
        document.addEventListener( 'resume', onResume.bind( this ), false );
        
        // TODO: Cordova a été chargé. Effectuez l'initialisation qui nécessite Cordova ici.
        console.log("device Ready");
        init(false);
    };

    function onPause() {
        // TODO: cette application a été suspendue. Enregistrez l'état de l'application ici.
    };

    function onResume() {
        // TODO: cette application a été réactivée. Restaurez l'état de l'application ici.
    };
});

function init(manual) {
    console.log("init() called");
    if (!window.localStorage.getItem("apiKey")) {
        window.localStorage.setItem("apiKey", generateApiKey(16));
    }
    if (!window.localStorage.getItem("initialized")) {
        window.localStorage.setItem("initialized", true);
        window.localStorage.setItem("usrKey", false);
        window.localStorage.setItem("accStatus", false);
        $("#indexSS").fadeOut();
    }
    if (window.localStorage.getItem("usrKey") != "false") {
        callAPI('checkAccStatus', false, false, false, false, false);
    }
    else {
        $("#indexSS").fadeOut();
        if (manual) {
            goToStep(1);
        }     
    }
}

function reinit() {
    window.localStorage.setItem("usrKey", false);
    window.localStorage.setItem("accStatus", false);
    document.getElementById("gsmInput2").value = "";
    document.getElementById("emailInput2").value = "";
    document.getElementById("validationCodeInput2").value = "";
    gsmField = false;
    emailField = false;
    $("#"+divToClose).slideUp();
    step = 0;
    $("#view" + step).slideDown();
    inProgress = false;
    divToClose = false;
    usrData = null;
}

var step = 0;

function goToStep(x) {
    if (x == "-") {
        if (parseInt(step) - 1 >= 0) {
            x = parseInt(step) - 1;
        }
    }
    else if (x == "+") {
        x = parseInt(step) + 1;
    }
    if (x == 1) {
        document.getElementById('gsmInput').value = '';
        clearInterval(timerInterval);
        reinit();
    }
    $("#view" + step).slideUp();
    $("#view" + x).slideDown();
    step = x;
}

var gsmField = false;
var emailField = false;
function checkEmptyField(step, execute) {
    if (step == "step1") {
        var gsmExt = $("#gsmExt2").val();
        var gsm = parseInt($("#gsmInput2").val());
        var email = $("#emailInput2").val();

        if (validateEmail(email)) { emailField = true; }
        else { emailField = false; }

        if (gsm.toString().length >= 4) { gsmField = true; }
        else { gsmField = false; }

        if (gsmField && emailField) {
            if (execute) {
                console.log("callAPI('checkAccStatus', " + gsmExt + ", " + gsm + ", " + email + ", false, false)");
                callAPI('checkAccStatus', gsmExt, gsm, email, false, false);
                document.getElementById("usrEmail").innerHTML = email;
            }
            else {
                document.getElementById("verifyBtn2").disabled = false;
            }            
        }
        else {
            document.getElementById("verifyBtn2").disabled = true;
        }
    }
    else if (step == "step2") {
        var vCode = $("#validationCodeInput2").val();

        if (vCode.length == 6) {
            if (execute) {
                callAPI('numValidateCode', false, false, false, vCode, false);
            }
            else {
                document.getElementById("validateBtn2").disabled = false;
            }
        }
        else {
            document.getElementById("validateBtn2").disabled = true;
        }
    }
    else if (step == "step3") {
        var vCode = $("#validationCodeInput3").val();

        if (vCode.length == 6) {
            if (execute) {
                callAPI('connectionValidation', false, false, false, vCode, false);
            }
            else {
                document.getElementById("validateBtn3").disabled = false;
            }
        }
        else {
            document.getElementById("validateBtn3").disabled = true;
        }
    }
    else if (step == "step4") {
        var vCode = $("#validationCodeInput4").val();

        if (vCode.length == 6) {
            if (execute) {
                callAPI('newDeviceValidation', false, false, false, vCode, false);
            }
            else {
                document.getElementById("validateBtn4").disabled = false;
            }
        }
        else {
            document.getElementById("validateBtn4").disabled = true;
        }
    }

    //var inputVal = document.getElementById(inputID).value;

    //if (inputID == "gsmInput") {
    //    inputVal = inputVal.replace(/\s+/g, ''); //Remove white spaces
    //    if (inputVal.length >= 4) {
    //        document.getElementById("verifyBtn").disabled = false;
    //    }
    //    else {
    //        document.getElementById("verifyBtn").disabled = true;
    //    }
    //}
    //else if (inputID == "emailInput") {
    //    if (validateEmail(inputVal)) {
    //        document.getElementById("verifyBtn2").disabled = false;
    //    }
    //    else {
    //        document.getElementById("verifyBtn2").disabled = true;
    //    }
    //}
    //else if (inputID == "validationCodeInput") {
    //    inputVal = inputVal.replace(/\s+/g, ''); //Remove white spaces
    //    if (inputVal.length == 6) {
    //        document.getElementById("validateBtn").disabled = false;
    //    }
    //    else {
    //        document.getElementById("validateBtn").disabled = true;
    //    }
    //}

}

function checkNumber() {
    var gsmExt = document.getElementById("gsmExt").value;
    var gsm = document.getElementById("gsmInput").value;
    var noZeroGsm = parseInt(gsm);
    document.getElementById("phoneNumber").innerHTML = "+" + gsmExt + " " + noZeroGsm;
    apiGetAccount("retrieveAccount", gsmExt, noZeroGsm);
}

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function checkEmail() {
    document.getElementById("usrEmail").innerHTML = document.getElementById("emailInput").value;
    apiGetAccount("retrieveAccount", gsmExt, noZeroGsm);
}

var timing;
var timerInterval;

function setTimer(target) {
    timing = 59;
    timerInterval = setInterval("tick('"+target+"');", 1000);
}

function tick(target) {
    if (timing > 0) {
        timing = parseInt(timing) - 1;
        displayTiming = ("0" + timing).slice(-2);
        if (target == "signup") {
            document.getElementById("SMSCntDwn").innerHTML = "00:" + displayTiming;
            document.getElementById("CallCntDwn").innerHTML = "00:" + displayTiming;
        }
        else if (target == "connect") {
            document.getElementById("SMSCntDwn2").innerHTML = "00:" + displayTiming;
            document.getElementById("CallCntDwn2").innerHTML = "00:" + displayTiming;
        }
    }
    else {
        clearInterval(timerInterval);
        timing = 59;
        inProgress = false;
        if (target == "signup") {
            document.getElementById("SMSCntDwn").style.display = "none";
            document.getElementById("resendSMSBtn").style.display = "";
            document.getElementById("CallCntDwn").style.display = "none";
            document.getElementById("recallBtn").style.display = "";
        }
        else if (target == "connect") {
            document.getElementById("SMSCntDwn2").style.display = "none";
            document.getElementById("resendSMSBtn2").style.display = "";
            document.getElementById("CallCntDwn2").style.display = "none";
            document.getElementById("recallBtn2").style.display = "";
        }
        
    }
}

var inProgress = false;

function retryValidation(todo) {
    if (!inProgress) {
        inProgress = true;
        if (todo == "sms") {
            console.log("Resend SMS");
        }
        else if (todo = "call") {
            console.log("Re Call");
        }
        document.getElementById("SMSCntDwn").innerHTML = "00:59";
        document.getElementById("CallCntDwn").innerHTML = "00:59";
        document.getElementById("SMSCntDwn").style.display = "";
        document.getElementById("resendSMSBtn").style.display = "none";
        document.getElementById("CallCntDwn").style.display = "";
        document.getElementById("recallBtn").style.display = "none";
        setTimer("signup");
    }
    
}

function apiGetAccount(todo, gsmExt, gsm) {
    console.log("apiGetAccount called");
    $.ajax({
        method: "GET",
        url: apiURL,
        dataType: "text",
        data: "todo=" + todo + "&apiKey=" + window.localStorage.getItem("apiKey") + "&gsmExt=" + gsmExt + "&gsm=" + gsm,
        success: function (data) {
            usrData = JSON.parse(data);
            console.log(data);
            window.localStorage.setItem("accStatus", usrData.accStatus);
            window.localStorage.setItem("usrKey", usrData.usrKey);
            executeInstruction(usrData.instruction);
        },
        error: function (result, status, error) {
            var err = JSON.parse(result.responseText);
            console.log(err.Message + "//" + status + "//" + error);
        }
    });		
}

function apiValidationCode(todo, validationCode) {
    console.log("apiValidationCode called");
    $.ajax({
        method: "GET",
        url: apiURL,
        dataType: "text",
        data: "todo=" + todo + "&apiKey=" + window.localStorage.getItem("apiKey") + "&usrKey=" + usrData.usrKey + "&vCode=" + validationCode,
        success: function (data) {
            $.extend(true, usrData, JSON.parse(data));
            console.log(usrData);
            window.localStorage.setItem("accStatus", usrData.accStatus);
            if (usrData.accStatus == 0) {
                //Still needs to be validated
            }
            else if (usrData.accStatus == 1) {
                // GMS validated, next step
                document.getElementById("companyIDPicRejected").style.display = "none";
                $("#companyIDPicFirst").fadeIn();
                goToStep(3);                
            }
        }
    });
}

function callAPI(todo, gsmExt, gsm, email, vCode, emailType) {
    console.log("callAPI(" + todo + ") triggered");
    if (gsmExt != false && gsm != false && email != false) {
        $.ajax({
            method: "GET",
            url: apiURL,
            dataType: "text",
            data: "todo=" + todo + "&gsmExt=" + gsmExt + "&gsm=" + gsm + "&email=" + email + "&apiKey=" + window.localStorage.getItem("apiKey"),
            success: function (data) {
                if (usrData == null) {
                    usrData = JSON.parse(data);
                }
                $.extend(true, usrData, JSON.parse(data));
                window.localStorage.setItem("usrKey", usrData.usrKey);
                console.log(usrData);
                executeInstruction(usrData.instruction);
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText
                alert('Error - ' + errorMessage);
            }
        });
    }
    else if (vCode != false) {
        $.ajax({
            method: "GET",
            url: apiURL,
            dataType: "text",
            data: "todo=" + todo + "&usrKey=" + window.localStorage.getItem('usrKey') + "&vCode=" + vCode + "&apiKey=" + window.localStorage.getItem("apiKey"),
            success: function (data) {
                if (usrData == null) {
                    usrData = JSON.parse(data);
                }
                $.extend(true, usrData, JSON.parse(data));
                console.log(usrData);
                executeInstruction(usrData.instruction);
            }
        });
    }
    else if (emailType != false) {
        $.ajax({
            method: "GET",
            url: apiURL,
            dataType: "text",
            data: "todo=" + todo + "&usrKey=" + window.localStorage.getItem('usrKey') + "&eType=" + emailType + "&apiKey=" + window.localStorage.getItem("apiKey"),
            success: function (data) {
                if (usrData == null) {
                    usrData = JSON.parse(data);
                }
                $.extend(true, usrData, JSON.parse(data));
                console.log(usrData);
                executeInstruction(usrData.instruction);
            }
        });
    }
    else {
        var usrKey = window.localStorage.getItem("usrKey");
        $.ajax({
            method: "GET",
            url: apiURL,
            dataType: "text",
            data: "todo=" + todo + "&usrKey=" + usrKey + "&apiKey=" + window.localStorage.getItem("apiKey"),
            success: function (data) {
                if (usrData == null) {
                    usrData = JSON.parse(data);
                }
                $.extend(true, usrData, JSON.parse(data));
                console.log(usrData);
                executeInstruction(usrData.instruction);
            }
        });
    }    
}

function cameraTakePicture() {
    console.log("cameraFunctionCalled");
    navigator.camera.getPicture(onSuccess, onFail, {
        quality: 50,
        sourceType: Camera.PictureSourceType.CAMERA,
        allowEdit: false,
        mediaType: Camera.MediaType.PICTURE,
        encodingType: Camera.EncodingType.JPEG,
        destinationType: Camera.DestinationType.FILE_URI,
        cameraDirection: Camera.Direction.BACK
    });

    function onSuccess(imageURI) {
        uploadPhoto(imageURI);
    }

    function onFail(message) {
        //alert('Failed because: ' + message);
    }
}

function uploadPhoto(imageURI) {
    console.log("STARTING UPLOAD FUNCNTION ...");
    var options = new FileUploadOptions();
    options.fileKey = "file";
    options.chunkedMode = false;
    options.fileName = imageURI.substr(imageURI.lastIndexOf('/') + 1);
    options.mimeType = "image/jpeg";

    var ft = new FileTransfer();

    ft.upload(imageURI, fileUploaderURL + "?usrKey=" + usrData.usrKey,
        function (result) {
            console.log(JSON.stringify(result));
            callAPI("licenceUploaded", false, false, false, false, false);
        },
        function (error) {
            console.log("ERROR ERROR ERROR TRIGGERD");
            console.log(JSON.stringify(error));
        }, options);
}

function generateApiKey(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

var divToClose = false;
function executeInstruction(instruction) {
    if (instruction == "validationCode") { //Brand new account
        goToStep(2);
        setTimer();
        $.extend(true, usrData, { "instruction": "none" });
    }
    else if (instruction == "companyIDValidation") { //GSM OK; Licence not uploaded
        document.getElementById("companyIDPicRejected").style.display = "none";
        $("#companyIDPicFirst").fadeIn();
        goToStep(3);
        $.extend(true, usrData, { "instruction": "none" });
    }
    else if (instruction == "companyIDValidationR") { //GSM OK; Licence uploaded and rejected
        document.getElementById("companyIDPicFirst").style.display = "none";
        $("#companyIDPicRejected").fadeIn();
        goToStep(3);
        $.extend(true, usrData, {"instruction": "none"});
    }
    else if (instruction == "licenceValidationPending") { //GSM OK; Licence uploaded; Licence validation pending
        goToStep(4);
        $.extend(true, usrData, { "instruction": "none" });
    }
    else if (instruction == "login") { // Account already Active
        window.location = "home.html";
        $.extend(true, usrData, { "instruction": "none" });
    }
    else if (instruction == "accountIncidents") { // Account suspended(5), blocked(6) or deleted(7)
        if (usrData.accStatus == 5) {
            document.getElementById("accSuspended").style.display = "";
            document.getElementById("accBlocked").style.display = "none";
            document.getElementById("accDeleted").style.display = "none";
            document.getElementById("accSuspendedBtns").style.display = "";
            document.getElementById("accBlockedBtns").style.display = "none";
            document.getElementById("accDeletedBtns").style.display = "none";
        }
        else if (usrData.accStatus == 6) {
            document.getElementById("accBlockedReason").innerHTML = usrData.statusReason;
            document.getElementById("accSuspended").style.display = "none";
            document.getElementById("accBlocked").style.display = "";
            document.getElementById("accDeleted").style.display = "none";
            document.getElementById("accSuspendedBtns").style.display = "none";
            document.getElementById("accBlockedBtns").style.display = "";
            document.getElementById("accDeletedBtns").style.display = "none";
        }
        else if (usrData.accStatus == 7) {
            document.getElementById("accDeletedReason").innerHTML = usrData.statusReason;
            document.getElementById("accSuspended").style.display = "none";
            document.getElementById("accBlocked").style.display = "none";
            document.getElementById("accDeleted").style.display = "";
            document.getElementById("accSuspendedBtns").style.display = "none";
            document.getElementById("accBlockedBtns").style.display = "none";
            document.getElementById("accDeletedBtns").style.display = "";
        }
        $("#view" + step).slideUp();
        divToClose = "accountStatus";
        $("#accountStatus").slideDown();
        $.extend(true, usrData, { "instruction": "none" });
    }
    else if (instruction == "disconnectionRequest") { // Connection on another device
        $("#view" + step).slideUp();
        divToClose = "disconnectionRequest";
        $("#disconnectionRequest").slideDown();
        $.extend(true, usrData, { "instruction": "none" });
    }
    else if (instruction == "changeDevice") { // Connection on another device
        $("#view" + step).slideUp();
        divToClose = "changeDevice";
        $("#changeDevice").slideDown();
        $.extend(true, usrData, { "instruction": "none" });
    }
    else if (instruction == "newDeviceValidation") { // Validate new connection
        if (divToClose != false) {
            $("#" + divToClose).slideUp();
        }       
        else {
            $("#view" + step).slideUp();
        }
        divToClose = "newDeviceValidation";
        document.getElementById("usrNewDeviceEmail").innerHTML = usrData.email;
        $("#newDeviceValidation").slideDown();
        $.extend(true, usrData, { "instruction": "none" }); 
    }
    else if (instruction == "connectionValidationCode") { // Connection on same device but account disconnected
        document.getElementById("usrConnectionEmail").innerHTML = usrData.email;
        if (divToClose != false) {
            $("#" + divToClose).slideUp();
        }
        else {
            $("#view" + step).slideUp();
        }
        $("#connectionValidationCode").slideDown();
        divToClose = "connectionValidationCode";
        $.extend(true, usrData, { "instruction": "none" });
    }
}
function updateLocalDB() {

}


// TEMP
