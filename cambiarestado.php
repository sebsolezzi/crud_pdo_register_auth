<?php

require_once './helpers/funciones.php';
require_once './config/db.php';
isAuth();

$status = 0;
$user_id = $_SESSION['id'];
$task_id = filter_var($_GET['tareaid'], FILTER_VALIDATE_INT);

if (!$task_id) {
    header('Location:/?msg=notid');
    return;
}
//BERIFICAR SI EXISTE LA TAREA
$query = $conn->prepare("SELECT * FROM tasks WHERE id = :id");
$query->bindParam(':id', $task_id);
$query->execute();
$task_db = $query->fetch(PDO::FETCH_ASSOC);

if (!$task_db) {
    header('Location:/?msg=notfound');
    return;
}
//VERIFICAR QUE LA TAREA QUE SE DESEA BORRA PERTENECE AL USUARIO LOGUEADO
if (!$task_db['user_id'] === $user_id) {
    header('Location:/?msg=forbidden');
    return; 
}


if($task_db['status']=== 0){
    $status = 1;
}else{
    $status = 0;
}

$query = $conn->prepare("UPDATE tasks SET status =:status WHERE id = :id");
$query->bindParam(':id', $task_id,PDO::PARAM_INT);
$query->bindParam(':status', $status,PDO::PARAM_INT);

if($query->execute()){
    header('Location:/?msg=updateok');
} else{
    header('Location:/?msg=error');
}



