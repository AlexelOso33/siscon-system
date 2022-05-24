<?php
    session_start();
    
    $db = 'sisconsy_'.$_SESSION['bd'];
    
    $user = 'root';
    $password = '';
    /* $user = 'sisconsy_pol_onl_admin';
    $password = 'polietileno25@33'; */
    $host = 'localhost';
    
    $conn = mysqli_connect($host, $user, $password, $db);
    $conn->set_charset("utf8");

    if (!$conn) {
        printf("Connect failed: %s\n", mysqli_connect_error());
    }
    
?>