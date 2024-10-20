<?php
require_once './templates/header.php';
require_once './helpers/funciones.php';
require_once './config/db.php';
isAuth();

$mensaje = [];
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $name = htmlspecialchars($_POST['name']);

    // Validaciones
    if (!$name) {

        $mensaje = ["tipo" => 'bg-danger', "mensaje" => "Debe completar el campo"];
    } else if (strlen($name) < 6) {

        $mensaje = ["tipo" => 'bg-danger', "mensaje" => "La tarea debe tener al menos 4 caracteres"];
    }

    if (empty($mensaje)) {
        //  GUARDA TAREA EN BASE DE DATOS
        $estado_tarea = 0;
        $query = $conn->prepare("INSERT INTO tasks (name, user_id, status) VALUES(:name, :user_id,:status)");
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
        $query->bindParam(':status', $estado_tarea, PDO::PARAM_INT);

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
        <h3 class="text-center mt-2">Crear Tarea</h3>
        <?php require_once './templates/alerta.php'; ?>
        <div class="col-12 col-md-10 col-xl-4 mx-auto mt-2">
            <form method="POST" action="create.php" class="border border-primary rounded p-2">
                <div class="mb-3">
                    <label for="name" class="form-label text-uppercase">Nombre Tarea</label>
                    <input type="text" name="name" class="form-control" aria-describedby="emailHelp">
                </div>
                <button type="submit" class="btn btn-primary d-block mx-auto mt-2">Crear</button>

            </form>
        </div>
    </div>
</div>


<?php
require_once './templates/footer.php';
?>