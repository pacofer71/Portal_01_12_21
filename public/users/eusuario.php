<?php
session_start();
if (!isset($_GET['id']) || !isset($_SESSION['user'])) {
    header("Location:index.php");
}
$id = $_GET['id'];

require dirname(__DIR__, 2) . "/vendor/autoload.php";

use Portal\Users;

$perfil = (new Users)->setUsername($_SESSION['user'])->getPerfil();
$usuario = (new Users)->read($id);

if ($perfil == 0 && $_SESSION['user']!=$usuario->username) {
    header("Location:index.php");
}

$prohibidos = ['admin.jpg', 'invitado.jpg', 'users.jpg'];

function isImagen($tipo)
{

    $tiposBuenos = [
        'image/jpeg',
        'image/bmp',
        'image/png',
        'image/webp',
        'image/gif',
        'image/svg-xml',
        'image/x-icon'
    ];
    return in_array($tipo, $tiposBuenos);
}
function comprobar($v, $c)
{
    global $error;
    if (strlen($v) == 0) {
        $error = true;
        $_SESSION[$c] = "**** Rellene este campo.";
    }
}
function comprobarExiste($v, $c)
{
    global $error;
    global $id;
    if (!(new Users)->setId($id)->isValid($v, $c)) {
        $error = true;
        $_SESSION[$c] = $v;
        ($c == 'username') ? $_SESSION['errn'] = "**** Usuario duplicado." : $_SESSION['errmail'] = "**** Mail duplicado.";
    }
}



if (isset($_POST['editar'])) {
    $error = false;
    $imagen = $usuario->img;
    $nombre = trim($_POST['username']);
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);
    $miperfil = (isset($_POST['perfil'])) ? $_POST['perfil'] : 0;

    comprobar($nombre, 'errn');
    comprobar($pass, 'errpass');

    comprobarExiste($nombre, 'username');
    comprobarExiste($email, 'email');

    if (is_uploaded_file($_FILES['img']['tmp_name'])) {
        if (isImagen($_FILES['img']['type'])) {
            $nombreImg = uniqid() . "_" . $_FILES['img']['name'];
            if (!move_uploaded_file($_FILES['img']['tmp_name'], dirname(__DIR__) . "/img/users/" . $nombreImg)) {
                $_SESSION['errimg'] = "No se pudo guardar la imagen";
                $error = true;
            } else {
                $imagen = "/img/users/$nombreImg";
                //borro la imagen antigua si no está en prohibidos
                if(!in_array( basename($usuario->img), $prohibidos)){
                    unlink(dirname(__DIR__).$usuario->img);
                }
            }
        } else {
            $_SESSION['errimg'] = "El campo debe ser de tipo imagen";
            $error = true;
        }
    }

    if (!$error) {
        //guardamos
        (new Users)->setUsername($nombre)
            ->setEmail($email)
            ->setPassword(hash('sha256', $pass))
            ->setPerfil($miperfil)
            ->setImg($imagen)
            ->update($id);
        $_SESSION['mensaje'] = "Usuario Actualizado";
        header("Location:index.php");
    } else {
        header("Location:{$_SERVER['PHP_SELF']}?id=$id");
    }
} else {
    //pintamos el form

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

        <title>Registrar Usuario</title>





    </head>

    <body style="background-color:silver">
        <h5 class="text-center mt-4">Registrar Usuario</h5>
        <div class="container mt-2">
            <div class="bg-success p-4 text-white rounded shadow-lg m-auto" style="width:35rem">
                <div class="text-center">
                    <img src="<?php echo ".." . $usuario->img; ?>" class="img-thumbnail mt-2" width="100rem" height="100rem">
                </div>
                <form name="cautor" action="<?php echo $_SERVER['PHP_SELF'] . "?id=$id" ?>" method='POST' enctype="multipart/form-data">

                    <div class="mb-3">
                        <label for="n" class="form-label">Nombre Usuario</label>
                        <input type="text" class="form-control" id="n" placeholder="Username" name="username" required value='<?php echo (isset($_SESSION['username'])) ? $_SESSION['username'] : $usuario->username; ?>'>
                        <?php
                        if (isset($_SESSION['errn'])) {
                            echo "<p class='text-danger'><b>{$_SESSION['errn']}</b></p>";
                            unset($_SESSION['errn']);
                            if (isset($_SESSION['username'])) unset($_SESSION['username']);
                        }
                        ?>
                    </div>
                    <div class="mb-3">
                        <label for="a" class="form-label">Email</label>
                        <input type="email" class="form-control" id="a" placeholder="Correo" name="email" required value='<?php echo (isset($_SESSION['email'])) ? $_SESSION['email'] : $usuario->email; ?>'>
                        <?php
                        if (isset($_SESSION['errmail'])) {
                            echo "<p class='text-danger'><b>{$_SESSION['errmail']}</b></p>";
                            unset($_SESSION['errmail']);
                            if (isset($_SESSION['email'])) unset($_SESSION['email']);
                        }
                        ?>
                    </div>
                    <div class="mb-3">
                        <label for="p" class="form-label">Password</label>
                        <input type="password" class="form-control" id="p" placeholder="Contraseña" name="password" required>
                        <?php
                        if (isset($_SESSION['errpass'])) {
                            echo "<p class='text-danger'><b>{$_SESSION['errpass']}</b></p>";
                            unset($_SESSION['errpass']);
                        }
                        ?>
                    </div>
                    <div class="mb-3">
                        <label for="f" class="form-label">Imagen Perfil</label>
                        <input class="form-control" type="file" id="f" name='img'>
                        <?php
                        if (isset($_SESSION['errimg'])) {
                            echo "<p class='text-danger'><b>{$_SESSION['errimg']}</b></p>";
                            unset($_SESSION['errimg']);
                        }
                        ?>
                    </div>
                    <?php
                    if ($perfil == 1) {
                        echo "<div class='mb-3'>";
                        echo "<label for='p' class='form-label'>Perfil</label>";
                        echo "<select class='form-control' name='perfil'>";
                        echo ($usuario->perfil==1) ? "<option value='1' selected>Admistrador</option>" : "<option value='1'>Admistrador</option>";
                        echo ($usuario->perfil==0) ? "<option value='0' selected>Usuario</option>" : "<option value='0'>Usuario</option>";
                        echo "</select>";
                    }
                    ?>
                    <div class='mt-3'>
                        <button type='submit' name="editar" class="btn btn-info"><i class="fas fa-edit"></i> Editar</button>
                        <a href="index.php" class="btn btn-warning"><i class="fas fa-backward"></i> Volver</a>
                    </div>

                </form>
            </div>

        </div>
    </body>

    </html>
<?php  } ?>