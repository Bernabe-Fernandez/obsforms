<?php

require 'vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Contactos
{
    private $config;
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
        $this->config = require __DIR__ . '/../config/config_correo.php';
    }

    public function procesarContacto($nombre, $correo, $telefono, $empresa, $mensaje)
    {
        try {
            $this->guardarContacto($nombre, $correo, $telefono, $empresa, $mensaje);
            $this->enviarCorreo($nombre, $correo, $telefono, $empresa, $mensaje);
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }

    private function guardarContacto(string $nombre, string $correo, string $telefono, string $empresa, string $mensaje)
    {
        try {
            $sql = "
                INSERT INTO vt_contactos
                (nombre, correo, telefono, empresa, mensaje)
                VALUES (?,?,?,?,?)
            ";

            $stmt = $this->conexion->prepare($sql);

            if (!$stmt) {
                throw new Exception("Error al preparar la sentencia SQL");
            }

            $stmt->bind_param(
                "sssss",
                $nombre,
                $correo,
                $telefono,
                $empresa,
                $mensaje
            );

            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar SQL: " . $stmt->error);
            }

            return true;

        } finally {
            if (isset($stmt) && $stmt instanceof mysqli_stmt) {
                $stmt->close();
            }
        }
    }

    private function enviarCorreo(string $nombre, string $correo, string $telefono, string $empresa, string $mensaje)
    {
        $mail = new PHPMailer(true);

        try {
           
            // SMTP CONFIG
           
            $mail->isSMTP();
            $mail->Host       = $this->config['smtp_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->config['smtp_user'];
            $mail->Password   = $this->config['smtp_password'];
            $mail->SMTPSecure = $this->config['smtp_secure'];
            $mail->Port       = $this->config['smtp_port'];

           
            // FROM
           
            $mail->setFrom(
                $this->config['from_email'],
                $this->config['from_name']
            );

            
            // DESTINATARIOS (PRINCIPALES)
            
            $mail->addAddress('becariosistemas@omnibandas.com.mx', 'José'); 
            $mail->addAddress('sistemas@omnibandas.com.mx', 'Bernabe');  
            
            
            // COPIAS 

            // $mail->addCC('correo', 'Marian');
            // $mail->addCC('correo', 'Dirección');
             

           
            // FORMATO
         
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8'; 
            $mail->Subject = 'Nuevo registro de contacto';

        
            // SANITIZACIÓN
        
            $nombreSafe   = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
            $correoSafe   = htmlspecialchars($correo, ENT_QUOTES, 'UTF-8');
            $telefonoSafe = htmlspecialchars($telefono, ENT_QUOTES, 'UTF-8');
            $empresaSafe  = htmlspecialchars($empresa, ENT_QUOTES, 'UTF-8');
            $mensajeSafe  = nl2br(htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'));

        
            // TEMPLATE HTML
            
            ob_start();
            include __DIR__ . '/../templates/formulario_contacto.php';
            $mail->Body = ob_get_clean();

            
            // ALT BODY (texto plano)
            
            $mail->AltBody =
                "Nuevo mensaje de contacto:\n\n" .
                "Nombre: $nombre\n" .
                "Correo: $correo\n" .
                "Teléfono: $telefono\n" .
                "Empresa: $empresa\n" .
                "Mensaje: $mensaje";

         
            // ENVÍO
           
            $mail->send();

        } catch (Exception $e) {
            throw new Exception("Error al enviar correo: " . $mail->ErrorInfo);
        }
    }
}

?>

