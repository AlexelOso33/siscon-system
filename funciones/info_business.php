<?php

    // Llamado a la tabla de información de la empresa //
    try {
        $sql1 = "SELECT * FROM `empresa`";
        $cons1 = $conn->query($sql1);
        $emp = $cons1->fetch_assoc();
    } catch (\Throwable $th) {
        die("Error fatal al intentar conectarse a la Base de datos.");
    }

?>