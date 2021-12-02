<?php
namespace Portal;
use PDO;
use PDOException;

class Conexion{
    protected static $conexion;

    public function __construct()
    {
        if(self::$conexion==null){
            self::crearConexion();
        }
    }
    public static function crearConexion(){
        //1.- Leemos configuracion de .config
        $fichero = dirname(__DIR__, 1)."/.config";
        $opciones=parse_ini_file($fichero);
        $dbname=$opciones['dbname'];
        $host=$opciones['host'];
        $usuario=$opciones['user'];
        $pass=$opciones['pass'];
        //2.- me creo el dns (descriptor de nombre de servicio)
        $dns="mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        //3.- Creamos la conexion
        try{
            self::$conexion=new PDO($dns, $usuario, $pass);
            //solo en desarrollo
            self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $ex){
            die("Error en la conexion!!! :".$ex->getMessage());
        }
    }

}
//echo "Probando conexion: <br>";
//$conexion=new Conexion();