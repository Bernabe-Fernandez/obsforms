<?php
    require 'vendor/autoload.php'; // Incluye PHPMailer

    // use PHPMailer\PHPMailer\PHPMailer;
    // use PHPMailer\PHPMailer\Exception;


    class Vacantes
    {
        private $conexion;
        public $data = array();

        // constructur de la clase, como parametro recibe la conexion a mysql para poderla encapsular dentro de la misma y poder hacer uso de ella
        public function __construct($conexion)
        {
            $this->conexion = $conexion;
        }


        public function createVacantes(string $nombre, string $correo, string $telefono, string $curriculum, string $mensaje)
        {
            // aca vamos a generar el registro en la base de datos
            try {
                $sqlInsert = "
                    INSERT INTO rh_solicitudes
                    (nombre, correo, telefono, curriculum, mensaje)
                    VALUES (?,?,?,?,?)
                ";

                $sentenciaInsert = $this->conexion->prepare($sqlInsert);

                if (!$sentenciaInsert) {
                    throw new Exception(
                        "Error al preparar la sentencia"
                    );
                }

                $sentenciaInsert->bind_param(
                    "sssss",
                    $nombre,
                    $correo,
                    $telefono,
                    $curriculum,
                    $mensaje
                );

                if (!$sentenciaInsert->execute()) {
                    throw new Exception(
                        $sentenciaInsert->error
                    );
                }

                return true;

            } finally {

                if (
                    isset($sentenciaInsert)
                    && $sentenciaInsert instanceof mysqli_stmt
                ) {
                    $sentenciaInsert->close();
                }
            }
        }



    }

?>