<!doctype html>
<html lang="en">

<?php
session_start();
if(!isset($_GET["postid"])){
    header("location: home.php");
    die();
}
else{
    include("utils.php");
    $post_data = get_post_data($_GET["postid"]);
    $username = htmlspecialchars(get_user_from_id($post_data["user_id"])["username"]);
}

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

    <title>Photo App</title>
</head>

<body class="darkbg">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <a class="navbar-brand" href="home.php"><strong>Photo App</strong></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="newpost.php">New Post</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <?php echo isset($_SESSION["loggedIn"]) ? '<a class="nav-link" href="user.php?user=' . $_SESSION['username'] . '">My Posts</a>' : '<a class="nav-link" href="login.php">Login</a>'; ?>
                </li>
                <li class="nav-item">
                    <?php echo isset($_SESSION["loggedIn"]) ? '<a class="nav-link" href="logout.php">Logout</a>' : '<a class="nav-link" href="signup.php">Sign Up</a>'; ?>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container bg-dark my-3 py-3 border-25" style="max-width: 70%;">
        
        <div class="row text-center">
            <div class="col-md-12">
                <a href="<?php echo $post_data["image_file"]; ?>" target="_blank">
                    <img class="img-fluid border-25 imgshadow" src="<?php echo $post_data["image_file"]; ?>" >
                </a>
                
            </div>
        </div>
        <div class="row">
            <div class="col ml-1 mt-2">
                <h2 class="text-light"><?php echo htmlspecialchars($post_data["title"]); ?></h2>
                <p class="text-light">By <a href="user.php?user=<?php echo $username; ?>"><?php echo $username; ?></a>
            </div>
        </div>
        <hr class="bg-secondary">
        <h1 class="text-light text-center mb-3">Other Posts</h1>
        <div class="row row-cols-5">
            <?php
            $posts = get_random_posts(20);
            foreach ($posts as $post) {
                echo "<div class='col'>";
                echo "<a href='post.php?postid=".$post["post_id"]."'>";
                echo "<div class='square mb-4 imgshadow' style='background-image: url(\"".$post["image_file"]."\");'></div>";
                echo "</a>";
                echo "</div>";
            }
            ?>
        </div>

    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>


</body>

</html>