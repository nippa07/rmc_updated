<?php
require('../db_connect.php');

if (!empty($_POST["bname"])) {
    $query = "SELECT * FROM users WHERE bname like '%" . $_POST["bname"] . "%' ORDER BY bname LIMIT 0,6";
    $result = $conn->query($query);
    if (!empty($result)) {
?>
        <ul id="business-list">
            <li class="add_business" data-toggle="modal" data-target="#businessModal">+ Add Business</li>
            <?php
            foreach ($result as $business) {
            ?>
                <li onClick="selectBusiness('<?php echo $business["bname"]; ?>', '<?php echo $business["id"]; ?>');"><?php echo $business["bname"]; ?></li>
            <?php } ?>
        </ul>
<?php }
} ?>