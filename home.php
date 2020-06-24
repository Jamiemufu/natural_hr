<?php

include "classes/User.php";
include "classes/Validate.php";
include "classes/Upload.php";

$success = "";
$user = new User();

//init id
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
}

//redirect if not logged in
if (!$user->isLoggedIn()) {
    header("location:index.php");
}

//set user vars
$user->setID($id);
$data = $user->getInfo();

//get currentuploads as array
$currentUploads = json_decode($data['uploads']);

if (isset($_POST['submit'])) {
    $upload = new Upload();
    $upload->setExtensions(array('jpg', 'jpeg', 'png', 'gif', 'doc', 'docx', 'txt', 'JPG'));
    $upload->setMaxSize(64);
    $upload->setDir("uploads");

    //pass username and id to make the file unique
    if ($upload->uploadFile('file', $data['username'], $data['id'])) {
        $success = $upload->getUploadName() . " Successfully uploaded...";
        //add onto array for users->uploads
        $currentUploads[] = $upload->getUploadName();
        //encode and add to the array for user uploads
        $user->setUploads(json_encode($currentUploads));
        $user->addUpload();
    } else {
        //get errors
        $success = $upload->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/style.css">
    <title>Vanilla PHP Login/Register</title>
</head>

<body>

<div class="container">
    <form action="" method="post" enctype="multipart/form-data">
        <h1><?php echo "{$data['firstname']} {$data['lastname']}" ?></h1>
        <!-- flex item -->
        <div class="form-group">
            <label for="file">Upload a document:
                <input type="file" name="file">
            </label>
        </div>
        <!-- flex item -->
        <?php echo "<span class='error' style='margin-top: 25px;'>{$success}</span>"; ?>
        <!--get current uploads and display on the front end-->
        <?php if (isset($currentUploads)) {
            echo "<h3>Current Uploads</h3>";
            echo "<ul>";
            foreach ($currentUploads as $upload) {
                echo "<li><a href='/uploads/{$upload}'>{$upload}</a></li>";
            }
            echo "</ul>";
        } ?>

        <p><a href="/logout.php">Logout?</a></p>
        <button type="submit" name="submit">Upload</button>
    </form>
</div>
<!--quick fix for reloading re-submitting form-->
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
</body>
</html>
