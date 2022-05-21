<?php 

namespace Model;

class Usuario extends ActiveRecord{

    //Base de Datos 
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'contrasena', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $contrasena;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []){
        $this -> id = $args['id'] ?? null;
        $this -> nombre = $args['nombre'] ?? '';
        $this -> apellido = $args['apellido'] ?? '';
        $this -> telefono = $args['telefono'] ?? '';
        $this -> email = $args['email'] ?? '';
        $this -> contrasena = $args['contrasena'] ?? '';
        $this -> admin = $args['admin'] ?? '0';
        $this -> confirmado = $args['confirmado'] ?? '0';
        $this -> token = $args['token'] ?? '';
      
    }

    //Mensajes ed validación para la creacion de una cuenta
    public function validarNuevaCuenta(){
        if(!$this -> nombre){
            self::$alertas['error'][] = 'El nombre del cliente es obligatorio';
        }
        if(!$this -> apellido){
            self::$alertas['error'][] = 'El apellido del cliente es obligatorio';
        }
        if(!$this -> email){
            self::$alertas['error'][] = 'El email del cliente es obligatorio';
        }
        if(!$this -> contrasena){
            self::$alertas['error'][] = 'La contrasena del cliente es obligatorio';
        }

        if(strlen($this -> contrasena) < 8){
            self::$alertas['error'][] = 'La contrasena debe tener al menos 8 caracteres';
        }

        return self::$alertas;
    }

    public function validarLogin(){
        if(!$this -> email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }

        if(!$this -> contrasena){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        return self::$alertas;
    }

    public function validarEmail() {
        if(!$this -> email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        return self::$alertas;
    }

    public function validarContrasena(){
        if(!$this->contrasena){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        if(strlen($this->contrasena) < 8){
            self::$alertas['error'][] = 'La contraseña debe tener al menos 8 caracteres';
        }

        return self::$alertas;
    }

    //Revisa si el usuario ya existe
    public function existeUsuario() {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this -> email . "' LIMIT 1";

        $resultado = self::$db -> query($query);

        if($resultado -> num_rows){
            self::$alertas['error'][] = 'El usuario ya está registrado';
        }

        return $resultado;
    }

    public function hashContrasena(){
        $this -> contrasena = password_hash($this -> contrasena, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this -> token = uniqid();
    }

    public function comprobarContraseñaYVerificado($contrasena){
        $resultado = password_verify($contrasena, $this->contrasena);

        if(!$resultado || !$this->confirmado){
            self::$alertas['error'][] = 'La contraseña es incorrecta o tu cuenta no ha sido confirmada';
        } else{
            return true;
        }
    }
}

?>