<!doctype html>
<html lang="en">

<?php
session_start();
include("utils.php");
if (!isset($_GET["user"])) {
    header("location: home.php");
    die();
} else {
    $username = $_GET["user"];
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

    <h1 class="display-3 text-center text-light mt-3"><?php echo $username ?>'s Posts</h1>
    <div class="container main-container border-25 bg-dark pt-4">
        <div class="row row-cols-5" id="latestUserPosts">
            <?php
            $first_page = paginate_latest_user_posts($_GET["user"], 0, 20);
            foreach ($first_page as $post) {
                echo "<div class='col'>";
                echo "<a href='post.php?postid=" . $post["post_id"] . "'>";
                echo "<div class='square mb-4 imgshadow' style='background-image: url(\"" . $post["image_file"] . "\");'></div>";
                echo "</a>";
                echo "</div>";
            }
            ?>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <script>
        var page = 1;
        var run = true;
        var more_pages = true;

        async function loadMoreImages() {
            var response = await fetch("latestuserposts.php?page=" + page + "&user=<?php echo $username ?>");
            var responseJson = await response.json();
            if (responseJson.length == 0) {
                more_pages = false;
            }
            for (var k in responseJson) {
                post = responseJson[k];

                var html = "<div class='col'>" +
                    "<a href='post.php?postid=" + post["post_id"] + "'>" +
                    "<div class='square mb-4 imgshadow' style='background-image: url(\"" + post["image_file"] + "\");'></div>" +
                    "</a>" +
                    "</div>";
                $("#latestUserPosts").append(html);
            }
        }

        setInterval(function() {
            var documentHeight = $(document).height();
            var scrollPos = $(window).height() + $(window).scrollTop();
            var scrollPct = (documentHeight - scrollPos) / documentHeight;
            if (more_pages) {
                if (scrollPct < 0.1) {
                    loadMoreImages();
                    page++;
                }
            }
        }, 500);
    </script>

</body>

</html>