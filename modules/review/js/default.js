var apiURL = "../modules/review/php/api.php";
// var apiURL = "php/api.php";

var contractorsListTimeout;
var contractorSelected = false;
var contractorRating = null;
var reviewCommentMaxChars = 512;


function searchContractor(param) {    
    $("#reviewContractorsListDiv").slideUp(300);
    contractorSelected = false;

    var todo = "searchContractor";
    if(param != ""){
        $.ajax({
            method: "GET",
            url: apiURL,
            dataType: "text",
            data: "todo=" + todo + "&param=" + encodeURIComponent(param),
            success: function (data) {
                clearTimeout(contractorsListTimeout);
                contactorList = JSON.parse(data);
                var contractorsListDiv = document.getElementById("reviewContractorsListDiv");
                var content = "";
                for( var i = 0; i < 5; i++ ){       
                    if(contactorList[i]){
                        content += '<span id="searchContractorLine'+i+'" class="reviewContractorListLine" onclick="selectContractor('+i+', '+contactorList[i].id+');">'+contactorList[i].bname+'</span>';
                    }
                    else{
                        if(i == 0){
                            content = '<span class="reviewContractorListLine" onclick="divToggle(\'addContractor\');"> NO CONTRACTOR HAS BEEN FOUND. <font color="blue">ADD NEW CONTRACTOR</font></span>';
                            break;
                        }
                        else{
                            break;
                        }                        
                    }                
                }
                $("#reviewContractorsListDiv").slideUp(300);
                    contractorsListTimeout = setTimeout(function () {
                        contractorsListDiv.innerHTML = content;
                        $("#reviewContractorsListDiv").slideDown(500);
                        checkContractorSelected();
                    }, 300);
                
            },
            error: function (result, status, error) {
                var err = JSON.parse(result.responseText);
                console.log(err.Message + "//" + status + "//" + error);
                checkContractorSelected();
            }
        });	        
    }	
}

function selectContractor(lineID, bizID){
    document.getElementById("reviewContractorSearchInput").disabled = true;
    $("#reviewContractorRemoveBtn").fadeIn();
    if(isNaN(lineID)){
        document.getElementById("reviewContractorSearchInput").value = lineID;
    }
    else{
        document.getElementById("reviewContractorSearchInput").value = document.getElementById("searchContractorLine"+lineID).innerHTML;
    }
    document.getElementById("contractorsID").value = bizID;
    contractorSelected = true;
    checkContractorSelected();
    $("#reviewContractorsListDiv").slideUp(300);
}

function changeContractor(){
    console.log("changeContractor");
    contractorSelected = false;
    document.getElementById("reviewContractorSearchInput").disabled = false;
    checkContractorSelected();
    document.getElementById("reviewContractorSearchInput").value = "";
}

function checkContractorSelected(){
    if (contractorSelected){
        document.getElementById("reviewCommentTextarea").disabled = false; 
        $("#reviewContent").removeClass("disabled"); 

    }
    else{
        contractorRating = null;
        for(var i = 1; i <= 5; i++){
            document.getElementById("star"+i).src = "../modules/review/img/starOff.png";
        } 
        document.getElementById("contractorsRate").value = "null";
        document.getElementById("contractorsID").value = "null";
        document.getElementById("reviewCommentTextarea").value = "";
        document.getElementById("reviewCommentTextarea").disabled = true;
        $("#reviewContent").addClass("disabled"); 
        $("#reviewContractorRemoveBtn").fadeOut();
    }
}

function rateContractor(rating){    
    if (contractorSelected){
        contractorRating = rating;
        document.getElementById("contractorsRate").value = contractorRating;
    }
}

function rateOver(rating){    
    if (contractorSelected){
        if(rating == 0){
            if(contractorRating != null){
                for(var i = 1; i <= 5; i++){
                    if(i <= contractorRating){
                        document.getElementById("star"+i).src = "../modules/review/img/starOn.png";
                    }
                    else{
                        document.getElementById("star"+i).src = "../modules/review/img/starOff.png";
                    }
                } 
            }
            else{
                for(var i = 1; i <= 5; i++){
                    document.getElementById("star"+i).src = "../modules/review/img/starOff.png";
                } 
            }        
        }
        else{
            for(var i = 1; i <= 5; i++){
                if(i <= rating){
                    document.getElementById("star"+i).src = "../modules/review/img/starOn.png";
                }
                else{
                    document.getElementById("star"+i).src = "../modules/review/img/starOff.png";
                }
            }
        }    
    }    
}

function reviewMaxCharsCount(itemID){
    if(itemID == null){itemID = "";}
    var myTextarea = document.getElementById("reviewCommentTextarea"+itemID);
    var totalChars = myTextarea.value.length;
    var commentAN = document.getElementById("reviewCommentTextarea"+itemID).value.replace(/\W/g, '');
    var remainingChars = reviewCommentMaxChars - totalChars;
    document.getElementById("reviewCommentMaxCounter"+itemID).innerHTML = remainingChars;
    if( commentAN.length < 30){
        document.getElementById("reviewCommentMaxCounter"+itemID).style.color = "red";
    }
    else{
        document.getElementById("reviewCommentMaxCounter"+itemID).style.color = "grey";
    }
}

var reviewError = false;
function checkReview(){
    if (contractorSelected){
        if(document.getElementById("contractorsID").value == "null"){
            reviewError = true;
            document.getElementById("reviewContractorSearchInput").style.borderColor = "red";
            setTimeout(function(){document.getElementById("reviewContractorSearchInput").style.borderColor = "black";}, 3000);
        }
        if(document.getElementById("contractorsRate").value == "null"){
            reviewError = true;
            document.getElementById("reviewRateDiv").style.border = "1px solid red";
            setTimeout(function(){document.getElementById("reviewRateDiv").style.border = "1px solid white";}, 3000);
        }
        var commentAN = document.getElementById("reviewCommentTextarea").value.replace(/\W/g, '');
        console.log(commentAN.length+"; ;"+commentAN);
        if(commentAN.length < 30){
            reviewError = true;
            document.getElementById("reviewCommentTextarea").style.borderColor = "red";
            setTimeout(function(){document.getElementById("reviewCommentTextarea").style.borderColor = "black";}, 3000);
        }
        if(!reviewError){
            var todo = "submitReview";
            $.ajax({
                method: "GET",
                url: apiURL,
                dataType: "text",
                data: "todo=" + todo + "&bizID=" + document.getElementById("contractorsID").value + "&rating=" + document.getElementById("contractorsRate").value + "&comment=" + encodeURIComponent(document.getElementById("reviewCommentTextarea").value),
                success: function (data) {
                    // var result = JSON.parse(data);
                    console.log(data);   
                    changeContractor();    
                },
                error: function (result, status, error) {
                    var err = JSON.parse(result.responseText);
                    console.log(err.Message + "//" + status + "//" + error);
                }
            });	        
        }
    }
}

var replyError = false;
function checkReply(itemID){
    var commentAN = document.getElementById("reviewCommentTextarea"+itemID).value.replace(/\W/g, '');
    console.log(commentAN.length+"; ;"+commentAN);
    if(commentAN.length < 30){
        replyError = true;
        document.getElementById("reviewCommentTextarea"+itemID).style.borderColor = "red";
        setTimeout(function(){document.getElementById("reviewCommentTextarea"+itemID).style.borderColor = "black";}, 3000);
    }
    if(!replyError){
        var todo = "submitReply";
        $.ajax({
            method: "GET",
            url: apiURL,
            dataType: "text",
            data: "todo=" + todo + "&reviewID=" + document.getElementById("replyReviewID"+itemID).value + "&comment=" + encodeURIComponent(document.getElementById("reviewCommentTextarea"+itemID).value),
            success: function (data) {
                // var result = JSON.parse(data);
                console.log(data);   
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth()+1; 
                var yyyy = today.getFullYear();
                if(dd<10) 
                {
                    dd='0'+dd;
                } 
                if(mm<10) 
                {
                    mm='0'+mm;
                } 
                document.getElementById("replyDiv"+itemID).innerHTML = '<span class="reviewContentText">'+document.getElementById("reviewCommentTextarea"+itemID).value+'</span> <span class="reviewDate">'+yyyy+'-'+mm+'-'+dd+'</span>';
            },
            error: function (result, status, error) {
                var err = JSON.parse(result.responseText);
                console.log(err.Message + "//" + status + "//" + error);
            }
        });	        
    }
}

function divToggle(toToggle){
    if(toToggle == "addContractor"){
        $("#blackBG").fadeIn(animationDelay);
        $("#fullPageDiv").fadeIn(animationDelay);
        $("#addContractorDiv").fadeIn(animationDelay);
    }    
}

var divCloseTimeout;
var animationDelay = 500;
function divClose(divID){
    if(divID == "addContractorDiv"){
        $("#"+divID).fadeOut(animationDelay);
        $("#blackBG").fadeOut(animationDelay);
        $("#fullPageDiv").fadeOut(animationDelay);
        divCloseTimeout = setTimeout(() => {
            document.getElementById("newContractorNameInput").value = "";
            document.getElementById("newContractorPhoneInput").value = "";
            document.getElementById("newContractorZipInput").value = "";            
        }, animationDelay);        
    }
}

var addContractorError = false;
function checkNewContractor(){
    var phoneNumberPattern = /^(1\s?)?((\([0-9]{3}\))|[0-9]{3})[\s\-]?[\0-9]{3}[\s\-]?[0-9]{4}$/;
    if(!phoneNumberPattern.test(document.getElementById("newContractorPhoneInput").value)){
        reviewError = true;
        document.getElementById("newContractorPhoneInput").style.borderColor = "red";
        setTimeout(function(){document.getElementById("newContractorPhoneInput").style.borderColor = "black";}, 3000);
    }
    if(document.getElementById("newContractorZipInput").value == ""){
        reviewError = true;
        document.getElementById("newContractorZipInput").style.border = "1px solid red";
        setTimeout(function(){document.getElementById("newContractorZipInput").style.border = "1px solid black";}, 3000);
    }
    var contractorNameAN = document.getElementById("newContractorNameInput").value.replace(/\W/g, '');
    if(contractorNameAN.length < 5){
        reviewError = true;
        document.getElementById("newContractorNameInput").style.borderColor = "red";
        setTimeout(function(){document.getElementById("newContractorNameInput").style.borderColor = "black";}, 3000);
    }
    if(!reviewError){
        var todo = "addContractor";
        $.ajax({
            method: "GET",
            url: apiURL,
            dataType: "text",
            data: "todo=" + todo + "&cName=" + encodeURIComponent(document.getElementById("newContractorNameInput").value) + "&cPhone=" + encodeURIComponent(document.getElementById("newContractorPhoneInput").value) + "&cZip=" + encodeURIComponent(document.getElementById("newContractorZipInput").value),
            success: function (data) {
                var result = JSON.parse(data);
                // console.log(result);   
                selectContractor(document.getElementById("newContractorNameInput").value, result);
                divClose("addContractorDiv");
            },
            error: function (result, status, error) {
                var err = JSON.parse(result.responseText);
                console.log(err.Message + "//" + status + "//" + error);
            }
        });	        
    }
}