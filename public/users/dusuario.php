<?php
if(!isset($_GET['id'])){
    header("Location:index.php");
}
$id=$_GET['id'];

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
$usuario=(new Users)->read($id);


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

    <title>Detalle usuario</title>





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
    <h3 class="text-center mt-2">Detalle Usuario (<?php echo $usuario->id; ?>)</h3>
    <div class="container mt-2">
       
    <div class="card mx-auto" style="width: 28rem;">
            <div class="mx-auto">
            <img src="<?php echo "..".$usuario->img; ?>" class="img-thumbnail mt-2" width="100rem" height="100rem">
            </div>
            <div class="card-body">
                <h5 class="card-title"><?php echo $usuario->username ?></h5>
                <p class="card-text"><?php echo $usuario->email ?></p>
                <p class="card-text">Perfil: <b><?php echo ($usuario->perfil==1) ?  "Administrador" : "Usuario"; ?></b></p>
                <a href="index.php" class="btn btn-primary"><i class="fas fa-backward"></i> Volver</a>
            </div>
        </div>
    </div>
</body>

</html>