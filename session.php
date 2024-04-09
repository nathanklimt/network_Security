<?php 
session_start();
if ($_SESSION['wicked_awesome_token']) {
    $wicked_awesome_token = $_SESSION['wicked_awesome_token'];
} else {
    $wicked_awesome_token = uniqid();
    $_SESSION['wicked_awesome_token'] = $wicked_awesome_token;
}
?>