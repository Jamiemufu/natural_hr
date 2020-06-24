<?php

include "classes/User.php";
include "classes/Validate.php";

$user = new User();

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
}
if (!$user->isLoggedIn()) {
    header("location:index.php");
}

$user->setID($id);
$data = $user->getInfo();

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
        <form action="" method="post">
            <h1><?php echo "{$data['firstname']} {$data['lastname']}" ?></h1>
            <!-- flex item -->
            <div class="form-group">
                <label for="email">Upload a document:
                    <input type="email" name="email" >
                </label>
            </div>
            <!-- flex item -->
            <p><a href="/logout.php">Logout?</a></p>
            <button type="submit" name="submit">Upload</button>
        </form>
    </div>
</body>
</html>
