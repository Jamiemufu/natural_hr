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
        'username' => array(
            'required' => true,
        ),
        'firstname' => array(
            'required' => true,
        ),
        'lastname' => array(
            'required' => true,
        ),
        'password' => array(
            'required' => true,
        ),
        'confirm' => array(
            'match' => true,
        ),
        'email' => array(
            'required' => true,
            'unique' => true,
        ),
    ));

    foreach ($validate->errors() as $error) {
        echo '<li style="color: red; font-size: 13px;">' . $error . '</li></br>';
    }

    if ($validate->valid()) {

        //insert data into database.
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);

        $register = $user->userRegistration();
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

            <h1>Register</h1>
            <!-- flex item -->
            <div class="form-group">
                <label for="username">Username:
                    <input type="text" name="username" required>
                </label>
            </div>
            <!-- flex item -->
            <div class="form-group">
                <label for="firstname">First name:
                    <input type="text" name="firstname" required>
                </label>
            </div>
            <!-- flex item -->
            <div class="form-group">
                <label for="lastname">Last name:
                    <input type="text" name="lastname" required>
                </label>
            </div>
            <!-- flex item -->
            <div class="form-group">
                <label for="email">Email Address:
                    <input type="email" name="email" required>
                </label>
            </div>
            <!-- flex item -->
            <div class="form-group">
                <label for="password">Password:
                    <input type="text" name="password" required>
                </label>
            </div>
            <!-- flex item -->
            <div class="form-group">
                <label for="confirm">Confirm Password:
                    <input type="text" name="confirm" required>
                </label>
            </div>
            <p><a href="/index.php">Already registered?</a></p>
            <button type="submit" name="submit">Register</button>
        </form>
    </div>

</body>
</html>
