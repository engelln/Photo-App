<?php
// How many posts should the script create?
// Note: This is the limit of total posts which should exist in the database. 
// If the total # of posts in the database >= this number, the script will not run.
$num_posts = 150;

// Which user should the script create the posts under? (This should be an existing user id)
// If you only created 1 user, the user id should be 1. Otherwise, you may need to check in the database for the user_id.
$user_id = 1;

// The directory of images to use
$dir = new DirectoryIterator("dummy_images");




include("connection.php");
include("utils.php");
function create_post_quiet($user_id, $name, $file_name, $file_location)
{
    global $conn;

    $post_id = uniqid("", true);

    // move file to images folder
    $destination = "images/".$post_id.$file_name;
    copy($file_location, $destination);

    // insert record into db
    $sql = "INSERT INTO posts (post_id, title, image_file, user_id) VALUES (?, ?, ?, ?)";

    if ($prepared_query = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($prepared_query, "sssi", $param_id, $param_name, $param_destination, $param_user_id);
        $param_id = $post_id;
        $param_name = $name;
        $param_destination = $destination;
        $param_user_id = $user_id;
        mysqli_stmt_execute($prepared_query);
    }

}


if(get_num_posts() < $num_posts){
    $posts = 0;
    foreach ($dir as $file){
        if(!$file->isDot()){
            $file_location = $file->getPath()."/".$file->getFilename();
            $post_name = "Example Post ".$posts;
            create_post_quiet($user_id, $post_name, $file->getFilename(), $file_location);
            $posts++;
        }
    
        if($posts > $num_posts){
            break;
        }
    }
    echo "Posts created.";
}
else{
    echo "Cannot run script! Total number of posts in db are more than the specified ".$num_posts;
}


?>