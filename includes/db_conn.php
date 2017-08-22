<?php
//establish link
// You will need to put your hook in here
     $link = mysqli_connect("localhost", "test", "test", "test");
    //if error print out error
      if (!$link) {
          echo "Error: Unable to connect to MySQL." . PHP_EOL;
          echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
          exit;
      }
?>