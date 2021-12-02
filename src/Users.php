<?php
namespace Portal;

use PDOException;
use PDO;
use Faker;

class Users extends Conexion{
    private $id;
    private $username;
    private $email;
    private $password;
    private $img;
    private $perfil;

    public function __construct()
    {
        parent::__construct();
        
    }
    //------------------------ CRUD ---------------------------------------------
    public function create(){
        $q="insert into users(username, email, password, img, perfil) values(:u, :e, :pa, :i, :pe)";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':u'=>$this->username,
                ':e'=>$this->email,
                ':pa'=>$this->password,
                ':i'=>$this->img,
                ':pe'=>$this->perfil,
            ]);
        }catch(PDOException $ex){
            die("Error al guardar usuario: ".$ex->getMessage());
        }

    }
    public function read(){

    }
    public function update(){

    }
    public function delete(){

    }
    public function readAll(){
        $q="select * from users order by username";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error al devolver todos los usuarios: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    //------------------------OTROS METODOS -------------------------------
    public function crearUsuarios($cant){
        if(!$this->hayUsuarios()){//me creo un usuario admin y otro normal, el resto con faker
            //el usuario admin
            (new Users)->setUsername("admin")
            ->setEmail("admin@gmail.com")
            ->setPassword(hash('sha256', 'secret0'))
            ->setImg("/img/users/admin.jpg")
            ->setPerfil(1)
            ->create();
            //el usuario normal
           (new Users)->setUsername("usuario")
            ->setEmail("usuario@gmail.com")
            ->setPassword(hash('sha256', 'usuario'))
            ->setImg("/img/users/users.png")
            ->setPerfil(0)
            ->create();
            //El resto de usuarios con faker
            $faker=Faker\Factory::create('es_ES');
           for($i=0; $i<$cant-2; $i++){
               (new Users)->setUsername($faker->unique()->username)
               ->setEmail($faker->unique()->freeEmail)
               ->setPassword($faker->sha256())
               ->setImg("/img/users/users.png")
               ->setPerfil($faker->numberBetween(0,1))
               ->create();

           }
        }    
    }
    public function hayUsuarios(): bool {
        $q="select * from users";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Erro al comprobar si hay usuarios: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return ($stmt->rowCount()!=0);
    }
    //devolver imagen
    public function getImg(){
        $q="select img from users where username=:u";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':u'=>$this->username
            ]);
        }catch(PDOException $ex){
            die("Erro al devolver imagen: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetch(PDO::FETCH_OBJ)->img;


    }
    //devolver perfil
    public function getPerfil(){
        $q="select perfil from users where username=:u";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':u'=>$this->username
            ]);
        }catch(PDOException $ex){
            die("Erro al devolver imagen: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetch(PDO::FETCH_OBJ)->perfil;


    }
    //comporbarUsuario
    public function comprobarUsuario($u, $p): bool{
        $q="select * from users where username=:u AND password=:p";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':u'=>$u,
                ':p'=>$p
            ]);
        }catch(PDOException $ex){
            die("Error al verificar usuario: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return ($stmt->rowCount()!=0);
    }

    //__________________________SETTERS_________________________________________
    

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }
    

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }
    

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
    

    /**
     * Set the value of img
     *
     * @return  self
     */ 
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Set the value of perfil
     *
     * @return  self
     */ 
    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;

        return $this;
    }
}