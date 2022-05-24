<?php
    
    //Comprobamos que existe la sesión iniciada y activa

    function usuario_autenticado() {
        if(!revisar_usuario()) {
            header('Location: ../index.php');
            session_destroy();
            session_unset();
            exit();
        }
    }
    
    function revisar_usuario() {
        return isset($_SESSION['usuario']);
    }
    
    session_start();
    usuario_autenticado();

    // ini_set('session.cookie_domain', '.siscon-system.com');
?>