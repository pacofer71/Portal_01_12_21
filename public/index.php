<?php
    require dirname(__DIR__)."/vendor/autoload.php";
    use Portal\Users;
    (new Users)->crearUsuarios(25);
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

    <title>Inicio</title>





</head>

<body style="background-color:#ff9800">
    <h3 class="text-center mt-2">INICIO</h3>
    <div class="container mt-2">
        <div class="d-flex justify-content-around">
            <a href="users/register.php" class="btn btn-success">Registrar</a>
            <a href="users/index.php" class="btn btn-secondary">Acceso Invitado</a>
            <a href="users/login.php" class="btn btn-success">Login</a>
        </div>

    </div>
</body>

</html>