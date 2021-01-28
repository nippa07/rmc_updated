<script type="text/javascript" src="js/default.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<link rel="stylesheet" href="css/default.css" />

<div id="reviewContainer">
    <input type="hidden" id="contractorsID" value="null"/>
    <img src="../modules/review/img/remove.png" id="reviewContractorRemoveBtn" style="display: none;" onclick="changeContractor()"/>
    <input type="text" id="reviewContractorSearchInput" placeholder="Search a contractor to review" onkeyup="searchContractor(this.value)"/>
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
        <div id="reviewCommentDiv">
            <textarea id="reviewCommentTextarea" disabled onkeyup="reviewMaxCharsCount();"></textarea>
            <span id="reviewCommentMaxCounter">512</span>
        </div>
        <input type="button" id="sendReviewBtn" value="Send" onclick="checkReview();"/>
    </div>
</div>