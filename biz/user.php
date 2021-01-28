<?php
require '../db_connect.php';

header("Content-type: application/json");

$char = isset($_GET['chars']) ? $_GET['chars'] : '';
$res = mysqli_query($conn, "SELECT id, bname, phone, city, state, zip FROM users WHERE bname LIKE '%".mysqli_real_escape_string($conn, $char)."%' AND not (claimed = '1')");
$rows = array();
while ($data = mysqli_fetch_assoc($res)) {
  $rows[] = $data;
}
echo 

json_encode($rows);
?>