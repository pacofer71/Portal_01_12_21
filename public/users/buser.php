<?php
session_start();
if(!isset($_POST['id']) || !isset($_SESSION['user'])){
    header("Location:index.php");
}
require dirname(__DIR__, 2)."/vendor/autoload.php";
use Portal\Users;
$usuario=unserialize($_POST['id']);
if($usuario->perfil!=1){
    header("Location:index.php"); //solo administradores puden estar aquí
}
//Dos cosas borra registro y archivo, este ultimo si NO es admin.jpg, invitado.jpg y users.jpg
(new Users)->delete($usuario->id);
$prohibidos=['admin.jpg', 'invitado.jpg', 'users.jpg'];
if(!in_array( basename($usuario->img), $prohibidos)){
    unlink(dirname(__DIR__).$usuario->img);
}
$_SESSION['mensaje']="Usuario Borrado.";
header("Location:index.php");