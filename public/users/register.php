<?php
session_start();
require dirname(__DIR__, 2) . "/vendor/autoload.php";
use Portal\Users;



$perfil = -1;

if (isset($_SESSION['user'])) {
    $perfil = (new Users)->setUsername($_SESSION['user'])->getPerfil();
}
if($perfil==0){
    header("Location:index.php");
}
$error=false;

function isImagen($tipo){

    $tiposBuenos=[
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
function comprobar($v, $c){
    global $error;
    if(strlen($v)==0){
        $error=true;
        $_SESSION[$c]="**** Rellene este campo.";
    }
}
function comprobarExiste($v, $c){
    global $error;
    if(!(new Users)->isValid($v, $c)){
        $error=true;
        $_SESSION[$c]=$v;
        ($c=='username') ? $_SESSION['errn']="**** Usuario duplicado." : $_SESSION['errmail']="**** Mail duplicado.";

    }
}
if(isset($_POST['registrar'])){

    $imagen="/img/users/default.png";
    
    $nombre=trim($_POST['username']);
    $email=trim($_POST['email']);
    $pass=trim($_POST['password']);
    $miperfil = (isset($_POST['perfil'])) ? $_POST['perfil'] : 0;

    comprobar($nombre, 'errn');
    comprobar($pass, 'errpass');

    comprobarExiste($nombre, 'username');
    comprobarExiste($email, 'email');

    if(is_uploaded_file($_FILES['img']['tmp_name'])){
        if(isImagen($_FILES['img']['type'])){
            $nombre="/img/users/".uniqid().$_FILE['img']['name'];
            if(!move_uploaded_file($_FILE['img']['tmp_name'], "..".$nombre)){
                $_SESSION['errimg']="No se pudo guardar la imagen";
                $error=true;
                
            }
            else{
                $imagen=$nombre;
            }

        }
        else{
            $_SESSION['errimg']="El campo debe ser de tipo imagen";
            $error=true;
        }
    }

    if(!$error){
        //guardamos
        (new Users)->setUsername($nombre)
        ->setEmail($email)
        ->setPassword(hash('sha256', $pass))
        ->setPerfil($miperfil)
        ->setImg($imagen)
        ->create();
        $_SESSION['mensaje']="Usuario creado";
        header("Location:index.php");
    }
    else{
        header("Location:{$_SERVER['PHP_SELF']}");
    }
}
else{
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
            <form name="cautor" action="<?php echo $_SERVER['PHP_SELF'] ?>" method='POST' enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="n" class="form-label">Nombre Usuario</label>
                    <input type="text" class="form-control" id="n" placeholder="Username" name="username" required value='<?php echo (isset($_SESSION['username'])) ? $_SESSION['username'] : ""; ?>'>
                    <?php
                        if(isset($_SESSION['errn'])){
                            echo "<p class='text-danger'><b>{$_SESSION['errn']}</b></p>";
                            unset($_SESSION['errn']);
                            if(isset($_SESSION['username'])) unset($_SESSION['username']);
                        }
                    ?>
                </div>
                <div class="mb-3">
                    <label for="a" class="form-label">Email</label>
                    <input type="email" class="form-control" id="a" placeholder="Correo" name="email" required value='<?php echo (isset($_SESSION['email'])) ? $_SESSION['email'] : ""; ?>' >
                    <?php
                        if(isset($_SESSION['errmail'])){
                            echo "<p class='text-danger'><b>{$_SESSION['errmail']}</b></p>";
                            unset($_SESSION['errmail']);
                            if(isset($_SESSION['email'])) unset($_SESSION['email']);
                        }
                    ?>
                </div>
                <div class="mb-3">
                    <label for="p" class="form-label">Password</label>
                    <input type="password" class="form-control" id="p" placeholder="Contraseña" name="password" required>
                    <?php
                        if(isset($_SESSION['errpass'])){
                            echo "<p class='text-danger'><b>{$_SESSION['errpass']}</b></p>";
                            unset($_SESSION['errpass']);
                        }
                    ?>
                </div>
               <div class="mb-3">
                        <label for="f" class="form-label">Imagen Perfil</label>
                        <input class="form-control" type="file" id="f" name='img'>
                        <?php
                        if(isset($_SESSION['errimg'])){
                            echo "<p class='text-danger'><b>{$_SESSION['errimg']}</b></p>";
                            unset($_SESSION['errimg']);
                        }
                    ?>
                </div>
                <?php
                if($perfil==1){
                echo <<<TXT
                    <div class="mb-3">
                    <label for="p" class="form-label">Perfil</label>
                    <select class="form-control" name="perfil">
                        <option value='1'>Admistrador</option>
                        <option value='0' selected>Usuario</option>
                    </select>
                    <div>
                    TXT;
                }
                ?>
                <div class='mt-3'>
                    <button type='submit' name="registrar" class="btn btn-info"><i class="fas fa-save"></i> Registrar</button>
                    <button type="reset" class="btn btn-warning"><i class="fas fa-broom"></i> Limpiar</button>
                </div>

            </form>
        </div>

    </div>
</body>

</html>
<?php  } ?>