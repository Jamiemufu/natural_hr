<?php

// simple basic logout - clear all sessions and redirect to index/login
include "classes/User.php";
$user = new User();
$user->logout();
