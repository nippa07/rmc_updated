<?php
    require 'db_connect.php';

        $rating1=$_POST['1rating'];
        $rating2=$_POST['2rating'];
        $rating3=$_POST['3rating'];
        $rating4=$_POST['4rating'];
        $rating5=$_POST['5rating'];
        $cost=$_POST['cost'];
        $review=$_POST['review'];
      
      
        $sql="INSERT INTO `review`(`professionalism`, `quality`, `communication`, `cleanliness`, `price`, `cost`, `review`)
       VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt= mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "There was an error";
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, 'sssssss', $rating1, $rating2, $rating3, $rating4, $rating5, $cost, $review);
            if (mysqli_stmt_execute($stmt));
            {
              echo "success";
              exit();
          }
        } 
?>