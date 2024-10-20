<?php
function debugear($dato)
{
    echo "<br>";
    var_dump($dato);
    echo "<br>";
    exit;
}
function isAuth()
{
    //session_start();
    if (!$_SESSION['id'] || !$_SESSION['username']) {
        header('Location:/login.php');
    }
}

function check_message($msg)
{
    $mensaje = [];
    if ($msg === 'taskok') {
        $mensaje = ["tipo" => 'bg-success', "mensaje" => "Tarea creada"];
    } else if ($msg === 'notid') {
        $mensaje = ["tipo" => 'bg-danger', "mensaje" => "Id no valido"];
    } else if ($msg === 'notfound') {
        $mensaje = ["tipo" => 'bg-danger', "mensaje" => "Tarea no encontrada"];
    } else if ($msg === 'forbidden') {
        $mensaje = ["tipo" => 'bg-danger', "mensaje" => "No autorizado"];
    } else if ($msg === 'deleteok') {
        $mensaje = ["tipo" => 'bg-success', "mensaje" => "Tarea borrada"];
    }else if($msg === 'deleterror'){
        $mensaje = ["tipo" => 'bg-danger', "mensaje" => "Error al borrar"];
    }else if($msg === 'updateok'){
        $mensaje = ["tipo" => 'bg-success', "mensaje" => "Tarea actualizad"];
    } else {
        $mensaje = [];
    }
    return $mensaje;
}
