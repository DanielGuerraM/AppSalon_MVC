<?php 

namespace Controllers;
use MVC\Router;
use Model\Usuario;
use Clases\Email;

    class LoginController {
        public static function login(Router $router) {
            $alertas = [];

            $auth = new Usuario;

            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $auth = new Usuario($_POST);

                $alertas = $auth -> validarLogin();

                if(empty($alertas)) {
                    //Comprobar que exita ese usuario
                    $usuario = Usuario::where('email', $auth->email);

                    if($usuario){
                        //Verificar la contrase├▒a
                        if($usuario->comprobarContrase├▒aYVerificado($auth->contrasena)){
                            //Autenteicar el usuario
                            session_start();

                            $_SESSION['id'] = $usuario->id;
                            $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                            $_SESSION['email'] = $usuario->email;
                            $_SESSION['login'] = true;

                            //Redireccionamiento
                            if($usuario->admin === "1"){
                                $_SESSION['admin'] = $usuario->admin ?? null;

                                header('location: /admin');
                            } else {
                                header('location: /cita');
                            }
                        }
                    } else {
                        Usuario::setAlerta('error', 'El usuario no existe');
                    }
                }
            }

            $alertas = Usuario::getAlertas();

            $router -> render('auth/login', [
                'alertas' => $alertas,
                'auth' => $auth
            ]);
        }

        public static function logout() {
            
            if (!$_SESSION['nombre']) {
                session_start();
              }

            $_SESSION = [];
            header('Location: /');
        }

        public static function olvide(Router $router) {
            
            $alertas = [];

            if($_SERVER['REQUEST_METHOD']=== 'POST'){
                $auth = new Usuario($_POST);
                $alertas = $auth->validarEmail();

                if(empty($alertas)){
                    $usuario = Usuario::where('email', $auth->email);

                    if($usuario && $usuario->confirmado === '1'){
                        //Generar  un token
                        $usuario->crearToken();
                        $usuario->guardar();

                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarInstrucciones();
                        //Alerta de exito
                        Usuario::setAlerta('exito', 'Revisa tu email');
                    } else {
                        Usuario::setAlerta('error', 'El usuario no existe o no est├í confirmado');

                    }
                }
            }
            $alertas = Usuario::getAlertas();

            $router -> render('auth/olvide-contrase├▒a', [
                'alertas' => $alertas
            ]);
        }

        public static function recuperar(Router $router) {
            
            $alertas = [];
            $error = false;

            $token = s($_GET['token']);

            //Buscar usuario por su token
            $usuario = Usuario::where('token', $token);

            if(empty($usuario)){
                Usuario::setAlerta('error', 'Token de verificaci├│n no v├ílido');
                $error = true;
            }

            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                //Leer la nueva contrase├▒a y guardarla
                $contrasena = new Usuario($_POST);
                $alertas = $contrasena -> validarContrasena();

                if(empty($alertas)){
                    $usuario->contrasena = null;

                    $usuario->contrasena = $contrasena->contrasena;
                    $usuario->hashContrasena();
                    $usuario->token = null;

                    $resultado = $usuario->guardar();

                    if($resultado){
                        header('location: /');
                    }
                }
            }

            $alertas = Usuario::getAlertas();
            $router->render('auth/recuperar-contrase├▒a', [
                'alertas' => $alertas,
                'error' => $error
            ]);
        }

        public static function crear (Router $router) {
            $usuario = new Usuario;
            //Alertas vacias
            $alertas = [];
            if($_SERVER['REQUEST_METHOD'] === 'POST'){

                $usuario -> sincronizar($_POST);
                $alertas = $usuario -> validarNuevaCuenta();

                //Revisar que alertas este vacio
                if(empty($alertas)){
                    //Verificar que el usuario no este previamente registrado
                   $resultado = $usuario->existeUsuario();

                   if($resultado -> num_rows){
                       $alertas = Usuario::getAlertas();
                   } else {
                        //hasehar la contrase├▒a
                        $usuario -> hashContrasena();
                    
                        //Generar un token ├║nico
                        $usuario -> crearToken();

                        //Enviar el email de verificaci├│n
                        $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                        $email -> enviarConfirmacion();

                        
                        //Crear el usuario
                        $resultado = $usuario->guardar();
                        //debuguear($usuario);
                        if($resultado){
                            echo 'Guardado Correctamente';
                            header('location: /mensaje');
                        }
                    }
                }
            }

            $router -> render('auth/crear-cuenta', [
                'usuario' => $usuario,
                'alertas' => $alertas
            ]);
        }

        public static function mensaje(Router $router){
            
            $router->render('auth/mensaje');
        }

        public static function confirmar(Router $router){

            $alertas = [];

            $token = s($_GET['token']);

            $usuario = Usuario::where('token', $token);

            if(empty($usuario)){
                //Mostrar mensaje de error
                Usuario::setAlerta('error', 'Cuenta no Valida');
            } else{
                //Modificar a usuarios confirmados  
                $usuario->confirmado = "1";
                $usuario->token = '';
                $usuario->guardar();
                Usuario::setAlerta('exito', 'Cuenta confirmada Correctamente');
            }
            //Obtener alertas
            $alertas = Usuario::getAlertas();
            //Renderizar la vista
            $router->render('auth/confirmar-cuenta', [
                'alertas' => $alertas
            ]);
        }
    }

?>