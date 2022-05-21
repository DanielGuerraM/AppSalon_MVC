<?php 

    namespace Clases;

    use PHPMailer\PHPMailer\PHPMailer;

    class Email {
        public $email;
        public $nombre;
        public $token;

        public function __construct($email, $nombre, $token){
            $this -> email = $email;     
            $this -> nombre = $nombre;     
            $this -> token = $token;     
        }

        public function enviarConfirmacion(){

            //Crear el obejto de email
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = '7bc4c27e249825';
            $mail->Password = '4939e92bace790';

            $mail -> setFrom('cuentas@appsalon.com');
            $mail -> addAddress('Cuentas@appsalon.com', 'Appsalon.com');
            $mail -> Subject = 'Confirmar tu cuenta';

            //set HTML
            $mail -> isHTML(TRUE);
            $mail -> CharSet = 'UTF-8';

            $contenido = "<html>";
            $contenido .= "<p><strong>Hola " . $this -> nombre . " </strong> Has creado tu cuenta en AppSalon, solo falta confirmarla dando click en el siguiente enlace</p>";
            $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/confirmar-cuenta?token=" . $this->token ."'>Confirmar Cuenta</a></p>";
            $contenido .= "<p>Si tú no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
            $contenido .=  "</html>";

            $mail -> Body = $contenido;

            //Enviar email
            $mail->send();
        }

        public function enviarInstrucciones(){

            //Crear el obejto de email
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = '7bc4c27e249825';
            $mail->Password = '4939e92bace790';

            $mail -> setFrom('cuentas@appsalon.com');
            $mail -> addAddress('Cuentas@appsalon.com', 'Appsalon.com');
            $mail -> Subject = 'Reestablece tu contraseña';

            //set HTML
            $mail -> isHTML(TRUE);
            $mail -> CharSet = 'UTF-8';

            $contenido = "<html>";
            $contenido .= "<p><strong>Hola " . $this -> nombre . " </strong> Has solicitado restablecer tu contraseña, ingresa en el siguiente enlace para hacerlo.</p>";
            $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/recuperar?token=" . $this->token ."'>Restablecer contraseña</a></p>";
            $contenido .= "<p>Si tú no solicitaste este servicio, puedes ignorar el mensaje</p>";
            $contenido .=  "</html>";

            $mail -> Body = $contenido;

            //Enviar email
            $mail->send();
        }
    }

?>