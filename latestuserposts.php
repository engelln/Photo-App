<?php


if(!isset($_GET["page"]) or !isset($_GET["user"])){
    header("location: home.php");
    die();
}
else{
    include("connection.php");
    include("utils.php");
    $page = $_GET["page"];
    $username = $_GET["user"];
    $posts = paginate_latest_user_posts($username, $page, 20);
    header("Content-type: application/json");
    echo json_encode($posts);
}

?>