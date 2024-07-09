<?php
session_start();
if (isset($_POST['logout'])) {
    session_destroy();
    setcookie('user', '', time() - 3600, '/');
    echo "success";
    exit;
  }
?>