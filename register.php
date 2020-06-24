<?php

include "classes/User.php";
include "classes/Validate.php";

$user = new User;

if ($user->isLoggedIn()) {
    header("location:home.php");
}

/**
 * @param $error
 * @param array $msg
 * @return array
 */
function seperateErrors($error, array $msg)
{
    if (strpos($error, "username") !== false) {
        $msg['username'] = $error;
    }
    if (strpos($error, "firstname") !== false) {
        $msg['firstname'] = $error;
    }
    if (strpos($error, "lastname") !== false) {
        $msg['lastname'] = $error;
    }
    if (strpos($error, "email") !== false) {
        $msg['email'] = $error;
    }
    if (strpos($error, "password") !== false) {
        $msg['password'] = $error;
    }
    return $msg;
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

    $msg = [];

    foreach ($validate->errors() as $error) {
        $msg = seperateErrors($error, $msg);
    }


    if ($validate->valid()) {

        //insert data into database.
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);

        $register = $user->userRegistration();
        header("location:login.php");
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
                <?php if (isset($msg['username'])) {
                    echo "<span class='error'>{$msg['username']}</span>";
                } ?>
                <input type="text" name="username" required value="<?= isset($_POST['username']) ? $_POST['username'] : ''; ?>">
            </label>
        </div>
        <!-- flex item -->
        <div class="form-group">
            <label for="firstname">First name:
                <?php if (isset($msg['firstname'])) {
                    echo "<span class='error'>{$msg['firstname']}</span>";
                } ?>
                <input type="text" name="firstname" required value="<?= isset($_POST['firstname']) ? $_POST['firstname'] : ''; ?>">
            </label>
        </div>
        <!-- flex item -->
        <div class="form-group">
            <label for="lastname">Last name:
                <?php if (isset($msg['lastname'])) {
                    echo "<span class='error'>{$msg['lastname']}</span>";
                } ?>
                <input type="text" name="lastname" required value="<?= isset($_POST['lastname']) ? $_POST['lastname'] : ''; ?>">
            </label>
        </div>
        <!-- flex item -->
        <div class="form-group">
            <label for="email">Email Address:
                <?php if (isset($msg['email'])) {
                    echo "<span class='error'>{$msg['email']}</span>";
                } ?>
                <input type="email" name="email" required value="<?= isset($_POST['email']) ? $_POST['email'] : ''; ?>">
            </label>
        </div>
        <!-- flex item -->
        <div class="form-group">
            <label for="password">Password:
                <?php if (isset($msg['password'])) {
                    echo "<span class='error'>{$msg['password']}</span>";
                } ?>
                <input type="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 6
                or more characters">
            </label>
        </div>
        <!-- flex item -->
        <div class="form-group">
            <label for="confirm">Confirm Password:
                <input type="password" name="confirm" required>
            </label>
        </div>
        <p><a href="/index.php">Already registered?</a></p>
        <button type="submit" name="submit">Register</button>
    </form>
</div>

</body>
</html>
