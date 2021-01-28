
    
    <main>
      <section class="container gallery-links">
          <h1>Gallery</h1>

          <div class="gallery-container">
              <?php
              include "../db_connect.php";

            $sql = "SELECT * FROM gallery WHERE userID= '$uid' ORDER BY orderGallery DESC";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              echo "SQL statement failed!";
            } else {
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);

              while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="d-inline-flex"  style="margin: 1em auto">
                    <a href="#">
                  <div style="background-image: url(./gallery/<?=$row["imgFullNameGallery"];?>);"></div>
                  <h3><?=$row["titleGallery"];?></h3>
                </a>
                    </div>
           <?php   }
            }
            ?>
          </div>
<?php
    if (!isset($_SESSION['uidUsers'])) {
        echo '';
        exit();
    }else{ ?>
          <div class="gallery-upload">
            <form action="gallery.inc.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="filename" placeholder="File Name">
                <input type="text" name="filetitle" placeholder="Image Title">
                <input id="file" type="file" name="file">
                <button type="submit" name="submit">UPLOAD</button>
            </form>
          </div>
<?php } 

?>

        </div>
      </section>

    </main>
    
  </body>
</html>