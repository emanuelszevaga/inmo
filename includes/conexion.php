<?php

function conectar()
{
    global $conexion; 
    
    // Usamos el constructor de mysqli para obtener un objeto
    $conexion = new mysqli("localhost", "root", "admi151003", "inmo", 3307); 

    /* comprobar la conexión */
    if ($conexion->connect_errno) {
        // En un entorno real, es mejor registrar esto que mostrarlo.
        printf("Falló la conexión: %s\n", $conexion->connect_error);
        exit();
    } else {
        $conexion->set_charset("utf8");
        return true; // Retorna true si todo fue bien
    }
}

function desconectar()
{
    global $conexion;
    if (isset($conexion)) { // Asegurarse de que la variable exista antes de intentar cerrar
        $conexion->close();
    }
}
?>