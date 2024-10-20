<?php
require_once './templates/header.php';
require_once './helpers/funciones.php';
require_once './config/db.php';
//session_start();
isAuth();

$mensaje = [];

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

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $name = htmlspecialchars($_POST['name']);
    $task_id = filter_var($_POST['task_id'], FILTER_VALIDATE_INT);


    // Validaciones
    if (!$name) {
        $mensaje = ["tipo" => 'bg-danger', "mensaje" => "Debe completar el campo"];
    } else if (strlen($name) < 6) {

        $mensaje = ["tipo" => 'bg-danger', "mensaje" => "La tarea debe tener al menos 4 caracteres"];
    }

    if (empty($mensaje)) {
        //  GUARDA TAREA EN BASE DE DATOS
        $estado_tarea = 0;
        $query = $conn->prepare("UPDATE tasks SET name=:name WHERE id = :id");
        $query->bindParam(':id', $task_id, PDO::PARAM_INT);
        $query->bindParam(':name', $name, PDO::PARAM_STR);

        if ($query->execute()) {
            header('Location:/?msg=taskok');
        } else {
            $mensaje = ["tipo" => 'bg-danger', "mensaje" => "Error al crear la tarea"];
        }
    }
}

?>

<div class="container">
    <div class="row">
        <h3 class="text-center mt-2">Editar Tarea</h3>
        <?php require_once './templates/alerta.php'; ?>
        <div class="col-12 col-md-10 col-xl-4 mx-auto mt-2">
            <form method="POST" class="border border-primary rounded p-2">
                <div class="mb-3">
                    <label for="name" class="form-label text-uppercase">Nombre Tarea</label>
                    <input type="text" name="name" class="form-control" value="<?php echo $task_db['name']; ?>">
                    <input type="hidden" name="task_id" value="<?php echo $task_db['id']; ?>">
                </div>
                <button type="submit" class="btn btn-primary d-block mx-auto mt-2">Crear</button>

            </form>
        </div>
    </div>
</div>


<?php
require_once './templates/footer.php';
?>