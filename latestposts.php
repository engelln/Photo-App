<?php


if(!isset($_GET["page"])){
    header("location: home.php");
    die();
}
else{
    include("connection.php");
    include("utils.php");
    $page = $_GET["page"];
    $posts = paginate_latest_posts($page, 20);
    header("Content-type: application/json");
    echo json_encode($posts);
}

?>