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
include("utils.php");

$username = $password = $confirm_password = "";
$username_msg = $password_msg = $confirm_password_msg = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submittedName = $_POST["registerUsername"];
    $submittedPassword = $_POST["registerPassword"];
    $submittedConfirm = $_POST["confirmPassword"];

    // a billion validations
    if (validate_nonempty($submittedName)) {
        if (validate_length($submittedName, 50)) {
            if (validate_username($submittedName)) {
                if (validate_nonempty($submittedPassword)) {
                    if (validate_length($submittedPassword, 100)) {
                        if ($submittedPassword == $submittedConfirm) {
                            create_user($submittedName, $submittedPassword);
                        } else {
                            $password_msg = "Fields must be equal.";
                            $confirm_password_msg = "Fields must be equal.";
                        }
                    } else {
                        $password_msg = "Password cannot be longer than 1000 characters.";
                    }
                } else {
                    $password_msg = "Password cannot be empty.";
                }
            } else {
                $username_msg = "Username is already taken.";
            }
        } else {
            $username_msg = "Username cannot be longer than 50 characters.";
        }
    } else {
        $username_msg = "Username must not be empty.";
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
            <h5 class="card-title text-center">Sign Up</h5>
            <hr class="bg-light">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="registerUsername">Username</label>
                    <input type="text" class="form-control <?php echo (!empty($username_msg)) ? 'is-invalid' : ''; ?>" name="registerUsername" id="registerUsername" required>
                    <span class="invalid-feedback"><?php echo $username_msg; ?></span>
                </div>
                <div class="form-group">
                    <label for="registerPassword">Password</label>
                    <input type="password" class="form-control <?php echo (!empty($password_msg)) ? 'is-invalid' : ''; ?>" id="registerPassword" name="registerPassword" required>
                    <span class="invalid-feedback"><?php echo $password_msg; ?></span>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" class="form-control <?php echo (!empty($confirm_password_msg)) ? 'is-invalid' : ''; ?>" id="confirmPassword" name="confirmPassword" required>
                    <span class="invalid-feedback"><?php echo $confirm_password_msg; ?></span>
                </div>
                <small>Already have an account? <a href="login.php">Login here.</a></small>
                <br>

                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>

        </div>

    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>


</body>

</html>