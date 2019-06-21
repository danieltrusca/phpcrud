<?php
    $conn=new mysqli("localhost", "dan", "652019td", "phpcrud");
    if ($conn->connect_error){
        die("could not connect to the database".$conn->connect_error);
    }
?>