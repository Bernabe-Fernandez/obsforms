<?php
    // por defecto carga las respuestas en json
    header('Content-Type: application/json; charset=utf-8');

    // header("Access-Control-Allow-Origin: *");
    // header("Access-Control-Allow-Methods: POST, OPTIONS");
    // header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

    // condicional, para evualar si el servidor tiene el metodo options
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204);
        exit();
    }

    // manda a llamar los archivos requeridos
    require_once 'class/Vacantes.php';
    require_once 'config/bd.php';

    try {
        // condicional, para evaluar que el metodo sea post
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            throw new Exception(
                "Método no permitido. Utilice POST."
            );
        }

        //tenemos la variable global $_post donde se guardan los datos enviados del formulario
        $nombre   = trim($_POST['nombre'] ?? '');
        $correo   = trim($_POST['correo'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $mensaje  = trim($_POST['mensaje'] ?? '');

        if (empty($nombre)) {
            throw new Exception("El nombre es obligatorio.");
        }

        if (empty($correo)) {
            throw new Exception("El correo es obligatorio.");
        }

        if (empty($telefono)) {
            throw new Exception("El teléfono es obligatorio.");
        }

        if (empty($mensaje)) {
            throw new Exception("El mensaje es obligatorio.");
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El correo electrónico no es válido.");
        }

        if (!preg_match('/^[0-9]{10}$/', $telefono)) {
            throw new Exception("El teléfono no es válido.");
        }

        // ====================================
        // VALIDACIÓN DEL CURRICULUM
        // ====================================

        if (!isset($_FILES['curriculum'])) {
            throw new Exception(
                "Debe adjuntar un currículum."
            );
        }

        $archivo = $_FILES['curriculum'];

        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            throw new Exception(
                "Error al subir el archivo."
            );
        }

        // Máximo 5 MB
        $maxSize = 5 * 1024 * 1024;

        if ($archivo['size'] > $maxSize) {
            throw new Exception(
                "El archivo excede los 5 MB permitidos."
            );
        }

        // Validar extensión
        $extension = strtolower(
            pathinfo(
                $archivo['name'],
                PATHINFO_EXTENSION
            )
        );

        if ($extension !== 'pdf') {
            throw new Exception(
                "Solo se permiten archivos PDF."
            );
        }

        // Validar MIME real
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        $mime = finfo_file(
            $finfo,
            $archivo['tmp_name']
        );

        finfo_close($finfo);

        if ($mime !== 'application/pdf') {
            throw new Exception(
                "El archivo no es un PDF válido."
            );
        }

        // ====================================
        // GUARDAR ARCHIVO
        // ====================================

        $carpeta = __DIR__ . '/uploads/curriculums/';

        if (!is_dir($carpeta)) {
            mkdir(
                $carpeta,
                0755,
                true
            );
        }

        $nombreArchivo =
            uniqid('cv_', true) .
            '.pdf';

        $rutaCompleta =
            $carpeta .
            $nombreArchivo;

        if (
            !move_uploaded_file(
                $archivo['tmp_name'],
                $rutaCompleta
            )
        ) {
            throw new Exception(
                "No fue posible guardar el archivo."
            );
        }

        // ====================================
        // BD
        // ====================================

        $bd = new Database();

        $conexion = $bd->getConnection();

        if (!$conexion) {
            throw new Exception(
                "Error de conexión."
            );
        }

        $vacante = new Vacantes($conexion);

        $vacante->procesarVacante(
            $nombre,
            $correo,
            $telefono,
            $rutaCompleta,
            $mensaje
        );

        echo json_encode([
            'icono' => 'success',
            'mensaje' => 'Solicitud enviada correctamente.'
        ]);

    } catch (Exception $e) {

        http_response_code(400);

        echo json_encode([
            'icono' => 'error',
            'mensaje' => $e->getMessage()
        ]);
    }
?>