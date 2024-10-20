<?php
require_once './templates/header.php';
require_once './helpers/funciones.php';
require_once './config/db.php';
isAuth();
$msg = $_GET['msg'] ?? '';
$mensaje = check_message($msg);

$id = $_SESSION['id'];

// VERIFICAR SI EL USUARIO YA TIENE TAREAS
$query = $conn->prepare("SELECT * FROM tasks WHERE user_id = :id  ORDER BY date DESC ");
$query->bindParam(':id', $id);
$query->execute();
$tasks = $query->fetchAll(PDO::FETCH_ASSOC);

if (!$tasks) {
    $mensaje = ["tipo" => 'bg-success', "mensaje" => "Aún no tienes tareas"];
}

?>


<div class="container">
    <h1 class="mt-3">Hola <span class="text-primary"> <?php echo $_SESSION['username']; ?></span></h1>
    <div class="row">
        <div class="col-12">
            <?php require_once './templates/alerta.php'; ?>
        </div>

        <div class="col-12">

            <?php if ($tasks): ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Tarea</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>

                            <tr class="<?php echo $task['status'] === 1 ? 'table-success' : 'table-danger'; ?>">
                                <td><?php echo $task['name']; ?> </td>
                                <td><?php echo $task['status'] === 1 ? 'Completada' : 'Pendiente'; ?></td>
                                <td>
                                    <a class="text-primary" href="editar.php?tareaid=<?php echo $task['id']; ?>">Editar</a>
                                    <a class="text-secondary" href="/cambiarestado.php/?tareaid=<?php echo $task['id']; ?>">Camb.Estado</a>
                                    <a class="text-danger" href="/borrartarea.php?tareaid=<?php echo $task['id']; ?>">Borrar</a>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
        </div>
    </div>
</div>
<?php require_once './templates/footer.php'; ?>