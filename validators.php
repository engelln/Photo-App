<?php
include("connection.php");

function validate_nonempty($str){
    return !empty(trim($str));
}

function validate_length($str, $len){
    return strlen(trim($str)) < $len;
}

function validate_username($username){
    global $conn;
    $sql = "SELECT * FROM users WHERE username = ?";
    $valid = false;
    if($prepared_query = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($prepared_query, "s", $param_username);
        $param_username = trim($username);
        if(mysqli_stmt_execute($prepared_query)){
            mysqli_stmt_store_result($prepared_query);
            if(mysqli_stmt_num_rows($prepared_query) == 0){
                $valid = true;
            }
            mysqli_stmt_close($prepared_query);
        }
    }
    return $valid;
}

function validate_image($filetype){
    $fileType = strtolower($filetype);
    if($fileType == "image/png" or $fileType == "image/jpg" or $fileType == "image/jpeg"){
        return true;
    }
    else{
        return false;
    }
}

?>