<?php
    class DataBase
    {
        private $host = "localhost";
        private $username = "root";
        private $password = "";
        private $dbname = "forms-pw";
        public $conexion;

        
        //metodo para abrir la conexion
        public function getConnection()
        {
            // definimos la conexion como nula al inicio
            $this->conexion = null;

            //seccion de try y catch para evitar cualqueir fallo
            try {
                // creamos la conexion a la bd
                $this->conexion = new mysqli($this->host, $this->username, $this->password, $this->dbname);

                // Verificar si hubo un error de conexión
                if ($this->conexion->connect_error) {
                    throw new Exception("Error de conexión: " . $this->conexion->connect_error);
                }

                // Si la conexión es exitosa, devolverla
                return $this->conexion;
                
            } catch (Exception $e) {
                // Aquí puedes registrar el error en un archivo log, por ejemplo
                error_log("Error de conexión: " . $e->getMessage(), 3, "/home/comsocom/api.comsop.com.mx/logs/myapp_errors.log");

                // Mostrar un mensaje general sin detalles sensibles
                echo "Hubo un problema al conectarse a la base de datos." . $e->getMessage();
            }
        }

        // Método para cerrar la conexión
        public function closeConnection()
        {
            if ($this->conexion) {
                $this->conexion->close();
            }
        }


    }

?>