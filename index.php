<?php

include "classes/User.php";
include "classes/Validate.php";

$user = new User;

if ($user->isLoggedIn()) {
    header("location:home.php");
}
//If our form has been submitted.
if (isset($_POST['submit'])) {

    extract($_POST);

    $validator = new Validate();
    //validate and check for the rules
    $validate = $validator->isValid($_POST, array(
        'password' => array(
            'required' => true,
        ),

        'email' => array(
            'required' => true,
        ),
    ));

    foreach ($validate->errors() as $error) {
        echo '<li style="color: red; font-size: 13px;">' . $error . '</li></br>';
    }

    if ($validate->valid()) {

        $user->setEmail($email);
        $user->setPassword($password);
        $login = $user->login();

        if ($login) {
            header("location:home.php");
        } else {
            $msg = 'Wrong email or password';
        }
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
        <form action="" method="post">

            <h1>Login</h1>

            <!-- flex item -->
            <div class="form-group">
                <label for="email">Email Address:
                    <input type="email" name="email" required >
                </label>
            </div>
            <!-- flex item -->
            <div class="form-group">
                <label for="password">Password:
                    <input type="text" name="password" required>
                </label>
            </div>
            <!-- flex item -->

            <p><a href="/register.php">Not Registered?</a></p>
            <button type="submit" name="submit">Login</button>
        </form>
    </div>

</body>
</html>
