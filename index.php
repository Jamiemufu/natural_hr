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
        'password' => array(
            'required' => true,
        ),

        'email' => array(
            'required' => true,
        ),
    ));

    $msg = [];

    foreach ($validate->errors() as $error) {
        $msg = seperateErrors($error, $msg);

    }

    if ($validate->valid()) {

        $user->setEmail($email);
        $user->setPassword($password);
        $login = $user->login();

        if ($login) {
            header("location:home.php");
        } else {
            $msg['login'] = 'Wrong email or password';
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
                <input type="text" name="password" required>
            </label>
        </div>
        <!-- flex item -->
        <?php if (isset($msg['login'])) {
            echo "<span class='error' style='margin-top: 25px;'>{$msg['login']}</span>";
        } ?>
        <p><a href="/register.php">Not Registered?</a></p>
        <button type="submit" name="submit">Login</button>
    </form>
</div>

</body>
</html>
