<?php
session_start();
if ($_SESSION['loggedIn'] == true) {
    header("location:../store.php");
} else {
    header("location:../login.php?login=false");
}

?>