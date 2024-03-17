<?php

session_start();

if (!empty($_SESSION['isConnect'])) {
    session_unset();
    session_destroy();
}

header('Location: ' . $_SERVER['HTTP_REFERER']);

