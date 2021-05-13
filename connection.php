<?php

$conn = mysqli_connect('localhost', 'root', '', 'photoapp', 3306);

if (mysqli_connect_errno($conn)) {
    die('Failed to connect to MySQL: '.mysqli_connect_error());
}

        
?>