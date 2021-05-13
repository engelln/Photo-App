<?php
include("connection.php");
function create_user($username, $password)
{
    global $conn;
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    if ($prepared_query = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($prepared_query, "ss", $param_username, $param_password);
        $param_username = trim($username);
        $param_password = password_hash($password, PASSWORD_DEFAULT);
        if (mysqli_stmt_execute($prepared_query)) {
            header("location: login.php");
        }
    }
}

function create_post($user_id, $name, $file)
{
    global $conn;

    $post_id = uniqid("", true);

    // move file to images folder
    $destination = "images/".$post_id.$file["name"];
    move_uploaded_file($file["tmp_name"], $destination);

    // insert record into db
    $sql = "INSERT INTO posts (post_id, title, image_file, user_id) VALUES (?, ?, ?, ?)";

    if ($prepared_query = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($prepared_query, "sssi", $param_id, $param_name, $param_destination, $param_user_id);
        $param_id = $post_id;
        $param_name = $name;
        $param_destination = $destination;
        $param_user_id = $user_id;
        if (mysqli_stmt_execute($prepared_query)) {
            header("location: post.php?postid=".$param_id);
            
        }
    }

}

function get_post_data($post_id){
    global $conn;
    $sql = "SELECT * FROM posts WHERE post_id = ?";
    if ($prepared_query = mysqli_prepare($conn, $sql)) {
        $prepared_query->bind_param("s", $post_id);
        $prepared_query->execute();
        $result = $prepared_query->get_result();
        $row = $result->fetch_assoc();
        return $row;
    }
}

function get_user_from_id($user_id){
    global $conn;
    $sql = "SELECT username FROM users WHERE user_id = ?";
    if ($prepared_query = mysqli_prepare($conn, $sql)) {
        $prepared_query->bind_param("s", $user_id);
        $prepared_query->execute();
        $result = $prepared_query->get_result();
        $row = $result->fetch_assoc();
        return $row;
    }
}

function get_num_posts(){
    global $conn;
    $sql = "SELECT COUNT(*) FROM posts";
    if ($prepared_query = mysqli_prepare($conn, $sql)) {
        $prepared_query->execute();
        $result = $prepared_query->get_result();
        $count = $result->fetch_row()[0];
        return $count;
    }
}

function get_random_posts($amount){
    global $conn;
    $num_posts = get_num_posts();
    $percentage = ($amount / $num_posts) + 0.01;

    $sql = "SELECT * FROM posts WHERE rand() <= ".$percentage." LIMIT ?";
    if ($prepared_query = mysqli_prepare($conn, $sql)) {
        $prepared_query->bind_param("i", $amount);
        $prepared_query->execute();
        $result = $prepared_query->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return $rows;
    }
}

function paginate_latest_posts($page, $posts_per_page){
    global $conn;
    $offset = $page * $posts_per_page;
    $sql = "SELECT * FROM posts ORDER BY time DESC LIMIT ? OFFSET ?";
    if ($prepared_query = mysqli_prepare($conn, $sql)) {
        $prepared_query->bind_param("ii", $posts_per_page, $offset);
        $prepared_query->execute();
        $result = $prepared_query->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return $rows;
    }
}

function get_user_from_username($username){
    global $conn;
    $sql = "SELECT * FROM users WHERE username = ?";
    if ($prepared_query = mysqli_prepare($conn, $sql)) {
        $prepared_query->bind_param("s", $username);
        $prepared_query->execute();
        $result = $prepared_query->get_result();
        $row = $result->fetch_assoc();
        return $row;
    }
}


function paginate_latest_user_posts($username, $page, $posts_per_page){
    global $conn;
    $offset = $page * $posts_per_page;
    $user_id = get_user_from_username($username)["user_id"];
    $sql = "SELECT * FROM posts WHERE user_id = ? ORDER BY time DESC LIMIT ? OFFSET ?";
    if ($prepared_query = mysqli_prepare($conn, $sql)) {
        $prepared_query->bind_param("iii", $user_id, $posts_per_page, $offset);
        $prepared_query->execute();
        $result = $prepared_query->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return $rows;
    }
}


?>