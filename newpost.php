<!doctype html>
<html lang="en">

<?php
session_start();
include("validators.php");
include("connection.php");
include("utils.php");

$title_msg = $file_msg = "";

if (!isset($_SESSION["loggedIn"]) or !isset($_SESSION["userID"])){
    header("location: login.php?after=newpost.php");
    die();
}
else{
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $submittedName = trim($_POST["postName"]);
        $file = $_FILES["postImage"];
        if(validate_nonempty($submittedName)){
            if(validate_length($submittedName, 100)){
                if(validate_image($file["type"])){
                    create_post($_SESSION["userID"], $submittedName, $file);
                }
                else{
                    $file_msg = "Uploaded file must be an image of type png or jpg.";
                }
            }
            else{
                $title_msg = "Post name cannot be longer than 100 characters.";
            }
        }
        else{
            $title_msg = "Post name cannot be empty.";
        }
    }
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

    <div class="card mx-auto mt-5 bg-dark text-light" style="width: 20%;">
        <div class="card-header">
            <h5 class="card-title text-center">Create New Post</h5>
            <hr class="bg-light">
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="postName">Post Name</label>
                    <input type="text" class="form-control <?php echo (!empty($title_msg)) ? 'is-invalid' : ''; ?>" id="postName" name="postName" required>
                    <span class="invalid-feedback"><?php echo $title_msg; ?></span>
                </div>
                <div class="form-group">
                    <label for="postImage">Image</label>
                    <input type="file" id="postImage" name="postImage" accept="image/png, image/jpeg" required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>

        </div>

    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>


</body>

</html>