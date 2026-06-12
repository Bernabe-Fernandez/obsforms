<?php
    require 'vendor/autoload.php'; // Incluye PHPMailer

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;


    class Solicitudes
    {
        private $conexion;
        public $data = array();

        // constructur de la clase, como parametro recibe la conexion a mysql para poderla encapsular dentro de la misma y poder hacer uso de ella
        public function __construct($conexion)
        {
            $this->conexion = $conexion;
        }


        public function createSolicitud(string $nombre, string $correo, string $telefono, string $empresa, string $mensaje)
        {
            
        }



    }

?>