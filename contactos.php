<?php
// Todas las respuestas serán JSON
header('Content-Type: application/json; charset=utf-8');

// Manejo de preflight OPTIONS (axios/fetch)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

// Cargar conexión a BD
require_once 'class/Contactos.php';
require_once 'config/bd.php';

try {

    // Solo permitir POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        throw new Exception("Método no permitido. Use POST.");
    }

    // Recibir datos enviados desde React (JSON)
    $data = json_decode(file_get_contents("php://input"), true);
    


    $nombre   = trim($data['nombre'] ?? '');
    $correo   = trim($data['correo'] ?? '');
    $telefono = trim($data['telefono'] ?? '');
    $empresa  = trim($data['empresa'] ?? '');
    $mensaje  = trim($data['mensaje'] ?? '');

   
    // VALIDACIONES


    if (empty($nombre))   throw new Exception("El nombre es obligatorio.");
    if (empty($correo))   throw new Exception("El correo es obligatorio.");
    if (empty($telefono)) throw new Exception("El teléfono es obligatorio.");
    if (empty($mensaje))  throw new Exception("El mensaje es obligatorio.");

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("El correo electrónico no es válido.");
    }

    if (!preg_match('/^[0-9]{10}$/', $telefono)) {
        throw new Exception("El teléfono debe tener 10 dígitos.");
    }

  
    // CONEXION EN BD
   

    $bd = new Database();
    $conexion = $bd->getConnection();

    if (!$conexion) {
        throw new Exception("Error de conexión a la base de datos.");
    }




    // ✔ Aquí sí se usa tu clase real
    $contacto = new Contactos($conexion);
    $contacto->procesarContacto($nombre, $correo, $telefono, $empresa, $mensaje);


    // ************ porque aca haces de nuevo una insercuion a la bd lo estas generando dos veces ************

    // $stmt = $conexion->prepare(
    //     "INSERT INTO vt_contactos (nombre, correo, telefono, empresa, mensaje)
    //      VALUES (?, ?, ?, ?, ?)"
    // );

    // $stmt->bind_param("sssss", $nombre, $correo, $telefono, $empresa, $mensaje);

    // if (!$stmt->execute()) {
    //     throw new Exception("Error al guardar: " . $stmt->error);
    // }

    // echo json_encode([
    //     "icono" => "success",
    //     "mensaje" => "Mensaje enviado correctamente."
    // ]);

} catch (Exception $e) {

    http_response_code(400);

    echo json_encode([
        "icono" => "error",
        "mensaje" => $e->getMessage()
    ]);
}
?>
