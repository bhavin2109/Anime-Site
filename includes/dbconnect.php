<?php 
    $server = "sql111.infinityfree.com";
    $username = "if0_42304850";
    $password = "bhaaviinn";
    $database = "if0_42304850_anime_site";

    $conn = mysqli_connect($server, $username, $password, $database);
    
    if (!$conn){
        die ("error: " . mysqli_connect_error());
    }
?>