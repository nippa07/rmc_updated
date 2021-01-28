<!-- <script type="text/javascript" src="js/default.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<link rel="stylesheet" href="css/default.css" /> -->

<link rel="stylesheet" href="./css/default.css" />

<div id="blackBG" style="display: none;"></div>
<div id="fullPageDiv" class="fullPageDiv" style="display: none;">
    <div id="addContractorDiv" class="inPageDiv" style="display: none;">
        <h2 class="card-title text-center">Add A Contractor</h2>
        <br/>
        <br/>
        <span class="newContractorLine">
            <span class="newContractorLabel"> Business Name :</span>
            <input type="text" id="newContractorNameInput" class="newContractorInput"/>
        </span>
        <br/>
        <span class="newContractorLine">
            <span class="newContractorLabel">Phone :</span>
            <input type="text" id="newContractorPhoneInput" class="newContractorInput" required autocomplete="none"/>
        </span>
        <br/>
        <span class="newContractorLine">
            <span class="newContractorLabel">Zip Code :</span>
            <input type="number" id="newContractorZipInput" class="newContractorInput" maxlength="13" />
        </span>
        <br/>
        <br/>
        <span class="newContractorLine">
            <input type="button" id="addContractorBtn" class="validBtn" value="Submit" onclick="checkNewContractor();">
            <input type="button" id="CancelAddContractorBtn" class="cancelBtn" value="Cancel" onclick="divClose('addContractorDiv');">
        </span>

    </div>
</div>

<div id="reviewContainer">
    <input type="hidden" id="contractorsID" value="null"/>
    <img src="../modules/review/img/remove.png" id="reviewContractorRemoveBtn" style="display: none;" onclick="changeContractor()"/>
    <input type="text" id="reviewContractorSearchInput" placeholder="Search contractor to review" onkeyup="searchContractor(this.value)"/>
    <div id="reviewContractorsListDiv" style="display: none;"></div>

    <div id="reviewContent" class="disabled">
        <div id="reviewRateDiv">
            <img src="../modules/review/img/starOff.png" id="star1" class="reviewStar" onclick="rateContractor(1);" onmouseover="rateOver(1);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star2" class="reviewStar" onclick="rateContractor(2);" onmouseover="rateOver(2);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star3" class="reviewStar" onclick="rateContractor(3);" onmouseover="rateOver(3);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star4" class="reviewStar" onclick="rateContractor(4);" onmouseover="rateOver(4);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star5" class="reviewStar" onclick="rateContractor(5);" onmouseover="rateOver(5);" onmouseout="rateOver(0);"/>
            <input type="hidden" id="contractorsRate" value="null"/>
        </div>
        <div id="reviewRateDiv">
            <img src="../modules/review/img/starOff.png" id="star1" class="reviewStar" onclick="rateContractor(1);" onmouseover="rateOver(1);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star2" class="reviewStar" onclick="rateContractor(2);" onmouseover="rateOver(2);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star3" class="reviewStar" onclick="rateContractor(3);" onmouseover="rateOver(3);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star4" class="reviewStar" onclick="rateContractor(4);" onmouseover="rateOver(4);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star5" class="reviewStar" onclick="rateContractor(5);" onmouseover="rateOver(5);" onmouseout="rateOver(0);"/>
            <input type="hidden" id="contractorsRate" value="null"/>
        </div><div id="reviewRateDiv">
            <img src="../modules/review/img/starOff.png" id="star1" class="reviewStar" onclick="rateContractor(1);" onmouseover="rateOver(1);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star2" class="reviewStar" onclick="rateContractor(2);" onmouseover="rateOver(2);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star3" class="reviewStar" onclick="rateContractor(3);" onmouseover="rateOver(3);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star4" class="reviewStar" onclick="rateContractor(4);" onmouseover="rateOver(4);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star5" class="reviewStar" onclick="rateContractor(5);" onmouseover="rateOver(5);" onmouseout="rateOver(0);"/>
            <input type="hidden" id="contractorsRate" value="null"/>
        </div><div id="reviewRateDiv">
            <img src="../modules/review/img/starOff.png" id="star1" class="reviewStar" onclick="rateContractor(1);" onmouseover="rateOver(1);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star2" class="reviewStar" onclick="rateContractor(2);" onmouseover="rateOver(2);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star3" class="reviewStar" onclick="rateContractor(3);" onmouseover="rateOver(3);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star4" class="reviewStar" onclick="rateContractor(4);" onmouseover="rateOver(4);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star5" class="reviewStar" onclick="rateContractor(5);" onmouseover="rateOver(5);" onmouseout="rateOver(0);"/>
            <input type="hidden" id="contractorsRate" value="null"/>
        </div><div id="reviewRateDiv">
            <img src="../modules/review/img/starOff.png" id="star1" class="reviewStar" onclick="rateContractor(1);" onmouseover="rateOver(1);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star2" class="reviewStar" onclick="rateContractor(2);" onmouseover="rateOver(2);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star3" class="reviewStar" onclick="rateContractor(3);" onmouseover="rateOver(3);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star4" class="reviewStar" onclick="rateContractor(4);" onmouseover="rateOver(4);" onmouseout="rateOver(0);"/>
            <img src="../modules/review/img/starOff.png" id="star5" class="reviewStar" onclick="rateContractor(5);" onmouseover="rateOver(5);" onmouseout="rateOver(0);"/>
            <input type="hidden" id="contractorsRate" value="null"/>
        </div>
        <div id="reviewCommentDiv">
            <textarea id="reviewCommentTextarea" disabled onkeyup="reviewMaxCharsCount();"></textarea>
            <span id="reviewCommentMaxCounter">512</span>
        </div>
        <input type="button" id="sendReviewBtn" class="validBtn" value="Send" onclick="checkReview();"/>
    </div>
</div> 

<script type="text/javascript" src="../modules/review/js/phone-format.js"></script>