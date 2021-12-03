<?php
session_start();
require dirname(__DIR__, 2) . "/vendor/autoload.php";

use Portal\Users;

$imagen = (isset($_SESSION['user'])) ?
    "../" . (new Users)->setUsername($_SESSION['user'])->getImg() : // /img/users/admin.jpg
    "../img/users/invitado.png";

$perfil = -1;
if (isset($_SESSION['user'])) {
    $perfil = (new Users)->setUsername($_SESSION['user'])->getPerfil();
}

$usuarios = (new Users)->readAll();

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- BootStrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- FONTAWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Inicio usuario</title>





</head>

<body style="background-color:#ff9800">
    <!-- NAVBAR ----------------------------->
    <div class="py-2 d-flex" style="background-color:silver">
        <div class="py-1">
            &nbsp;<img src="<?php echo $imagen ?>" class="rounded-circle" height="40rem" width="40rem">
        </div>
        <div class="py-2">
            &nbsp;<a href="../" style="text-decoration:none; color:white"><i class="fas fa-home"></i> INICIO</a>

        </div>
        <?php
        if (!isset($_SESSION['user'])) {
            echo <<<TXT
            <div class="py-2">
            &nbsp;&nbsp;<a href="register.php" style="text-decoration:none; color:blue">Registrarse</a>
            </div>
            <div class="py-2">
            &nbsp;&nbsp;<a href="login.php" style="text-decoration:none; color:blue">Login</a>
            </div>
            TXT;
        }

        ?>
        <div class="ms-auto">
            <input class="form-control" type="text" disabled value="<?php echo (isset($_SESSION['user'])) ? $_SESSION['user'] : "Invitado"; ?>">
        </div>
        <div>
            &nbsp;&nbsp;<a class="btn btn-info" href="<?php echo isset(($_SESSION['user'])) ? "cerrar.php" : "#" ?>"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>

    </div>
    <!-- FIN NAV BAR -->
    <h3 class="text-center mt-2">Usuarios del Portal</h3>
    <div class="container mt-2">
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo <<<TXT
                <div class="alert alert-primary mb-2" role="alert">
                <b>Información: </b>{$_SESSION['mensaje']}
                </div>
        TXT;
            unset($_SESSION['mensaje']);
        }
        ?>
        <?php
        if ($perfil == 1) {
            echo <<<TXT
        <a href="register.php" class="btn btn-success"><i class="fas fa-user-plus"></i> Crear Usuario</a>
        TXT;
        }
        ?>
        <table class="table table-light table-striped mt-4">
            <thead>
                <tr>
                    <th scope="col">Información</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Perfil</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($usuarios as $item) {
                    $color = ($item->perfil == 1) ? "red" : "blue";
                    echo "<tr>";
                    echo "<th scope='row'><a href='dusuario.php?id={$item->id}' class='btn btn-info'><i class='fas fa-info'></i></th>";
                    echo "<td style='color:$color'>{$item->username}</td>";
                    echo "<td style='color:$color'>{$item->email}</td>";
                    echo "<td style='color:$color'>{$item->perfil}</td>";
                    echo "<td>";
                    if ($perfil == 1) {
                        $user = serialize($item);
                        //soy admin veré todo los botones
                        echo <<<TXT
            <form name="q" method='POST' action="buser.php">
            <input type='hidden' name='id' value='$user' />
            <a href="eusuario.php?id={$item->id}" class="btn btn-warning">
            <i class="fas fa-edit"></i>
            </a>
            <button type='submit' class='btn btn-danger' onclick="return confirm('¿Borrar Usuario?');"><i class="fas fa-trash"></i></button>
            </form>
            TXT;
                    } elseif ($perfil == 0) {
                        //soy usuario normal solo el boton editar para mi
                        if ($_SESSION['user'] == $item->username) {
                            echo "<a href='eusuario.php?id={$item->id}' class='btn btn-warning'>";
                            echo "<i class='fas fa-edit'></i></a>";
                        } else {
                            echo "<button class='btn btn-danger' disabled><i class='fas fa-exclamation-circle'></i></button>";
                        }
                    } else {
                        //nada de nada
                        echo "<a href='login.php' class='btn btn-info'><i class='fas fa-sign-in-alt'</a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>