<!doctype html>
<html lang="en">

<?php
session_start();

if (isset($_SESSION["loggedIn"])){
    header("location: home.php");
    die();
}

include("connection.php");
include("validators.php");

$username = $password = "";
$error = false;
$msg = "Invalid credentals.";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submittedName = $_POST["loginUsername"];
    $submittedPassword = $_POST["loginPassword"];

    $sql = "SELECT user_id, username, password FROM users WHERE username = ?";

    if ($prepared_query = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($prepared_query, "s", $param_username);
        $param_username = $submittedName;
        if (mysqli_stmt_execute($prepared_query)) {
            mysqli_stmt_store_result($prepared_query);
            if (mysqli_stmt_num_rows($prepared_query) == 1) {
                mysqli_stmt_bind_result($prepared_query, $user_id, $username, $password_hash);
                if (mysqli_stmt_fetch($prepared_query)) {
                    if (password_verify($submittedPassword, $password_hash)) {
                        session_start();
                        $_SESSION["loggedIn"] = true;
                        $_SESSION["userID"] = $user_id;
                        $_SESSION["username"] = $username;
                        if(isset($_GET["after"])){
                            header("location: ".$_GET["after"]);
                        }
                        else{
                            header("location: home.php");
                        }
                        
                    } else {
                        $error = true;
                    }
                }
            } else {
                $error = true;
            }
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
            <h5 class="card-title text-center">Login</h5>
            <hr class="bg-light">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="loginUsername">Username</label>
                    <input type="text" class="form-control <?php echo $error ? 'is-invalid' : ''; ?>" id="loginUsername" name="loginUsername" required>
                </div>
                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <input type="password" class="form-control <?php echo $error ? 'is-invalid' : ''; ?>" id="loginPassword" name="loginPassword" required>
                    <span class="invalid-feedback"><?php echo $msg; ?></span>
                </div>
                <small>Need an account? <a href="signup.php">Create one here.</a></small>
                <br>

                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>

        </div>

    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>


</body>

</html>