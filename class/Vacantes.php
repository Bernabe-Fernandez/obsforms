<?php


    require 'vendor/autoload.php'; // Incluye PHPMailer

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;


    class Vacantes
    {
        
        private $config;

        private $conexion;
        public $data = array();


        // constructur de la clase, como parametro recibe la conexion a mysql para poderla encapsular dentro de la misma y poder hacer uso de ella
        public function __construct($conexion)
        {
            $this->conexion = $conexion;
            $this->config = require __DIR__ . '/../config/config_correo.php';
        }

        public function procesarVacante($nombre, $correo, $telefono, $curriculum, $mensaje)
{
            try {
                $this->createVacantes($nombre, $correo, $telefono, $curriculum, $mensaje);
                $this->sendVacantes($nombre, $correo, $telefono, $mensaje, $curriculum);
            } catch (Exception $e) {
                error_log($e->getMessage());
                throw $e;
            }
        }

        private function createVacantes(string $nombre, string $correo, string $telefono, string $curriculum, string $mensaje)
        {
            // Obtener únicamente el nombre del archivo
            $nombreArchivo = basename($curriculum);

            try {
                $sqlInsert = "
                    INSERT INTO rh_solicitudes
                    (nombre, correo, telefono, curriculum, mensaje)
                    VALUES (?,?,?,?,?)
                ";

                $sentenciaInsert = $this->conexion->prepare($sqlInsert);

                if (!$sentenciaInsert) {
                    throw new Exception("Error al preparar la sentencia");
                }

                $sentenciaInsert->bind_param(
                    "sssss",
                    $nombre,
                    $correo,
                    $telefono,
                    $nombreArchivo,
                    $mensaje
                );

                if (!$sentenciaInsert->execute()) {
                    throw new Exception($sentenciaInsert->error);
                }

                return true;

            } finally {
                if (isset($sentenciaInsert) && $sentenciaInsert instanceof mysqli_stmt) {
                    $sentenciaInsert->close();
                }
            }
        }

        private function sendVacantes(
            string $nombre,
            string $correo,
            string $telefono,
            string $mensaje,
            string $curriculum
        ) {
            $mail = new PHPMailer(true);

            try {
                // =========================
                // SMTP CONFIG
                // =========================
                $mail->isSMTP();
                $mail->Host       = $this->config['smtp_host'];
                $mail->SMTPAuth   = true;
                $mail->Username   = $this->config['smtp_user'];
                $mail->Password   = $this->config['smtp_password'];
                $mail->SMTPSecure = $this->config['smtp_secure'];
                $mail->Port       = $this->config['smtp_port'];

                // =========================
                // FROM / TO
                // =========================
                $mail->setFrom(
                    $this->config['from_email'],
                    $this->config['from_name']
                );

                $mail->addAddress('recursoshumanos@omnibandas.com.mx', 'RRHH');
                $mail->addBCC('sistemas@omnibandas.com.mx', 'Sistemas');    
                $mail->addBCC('becariosistemas@omnibandas.com.mx', 'Sistemas');    

                $mail->Subject = 'Nueva solicitud de vacante';

                // =========================
                // FORMATO
                // =========================
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';

                // =========================
                // SANITIZACIÓN
                // =========================
                $nombreSafe   = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
                $correoSafe   = htmlspecialchars($correo, ENT_QUOTES, 'UTF-8');
                $telefonoSafe = htmlspecialchars($telefono, ENT_QUOTES, 'UTF-8');
                $mensajeSafe  = nl2br(htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'));

                // =========================
                // TEMPLATE
                // =========================
                ob_start();
                include __DIR__ . '/../templates/correo_vacante.php';
                $mail->Body = ob_get_clean();

                // =========================
                // ALT BODY (texto plano)
                // =========================
                $mail->AltBody =
                    "Nueva vacante recibida:\n\n" .
                    "Nombre: $nombre\n" .
                    "Correo: $correo\n" .
                    "Teléfono: $telefono\n" .
                    "Mensaje: $mensaje";

                // =========================
                // ADJUNTO CV (ruta en servidor)
                // =========================
                if (!empty($curriculum) && file_exists($curriculum)) {
                    $mail->addAttachment($curriculum);
                }

                // =========================
                // ENVÍO
                // =========================
                $mail->send();

            } catch (Exception $e) {
                throw new Exception("Error al enviar el correo: " . $mail->ErrorInfo);
            }
        }

    }

?>