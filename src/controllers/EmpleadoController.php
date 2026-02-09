<?php

require_once '../models/Empleado.php';

// Obtener la acción del cliente
$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'obtener':
        obtenerEmpleados();
        break;
    case 'crear':
        crearEmpleado();
        break;
    case 'actualizar':
        actualizarEmpleado();
        break;
    case 'eliminar':
        eliminarEmpleado();
        break;
    case 'obtenerPorId':
        obtenerEmpleadoPorId();
        break;
    case 'catalogos':
        obtenerCatalogos();
        break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
}

function obtenerEmpleados() {
    include '../config/conexion.php';
    $empleado = new Empleado($conexion);
    $empleados = $empleado->obtenerTodos();
    echo json_encode($empleados);
    $conexion->close();
}

function crearEmpleado() {
    include '../config/conexion.php';
    
    // Validar que se envíen datos
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        return;
    }

    // Obtener datos del formulario
    $datos = json_decode(file_get_contents("php://input"), true);

    $empleado = new Empleado($conexion);
    $empleado->first_name = $datos['first_name'] ?? '';
    $empleado->last_name = $datos['last_name'] ?? '';
    $empleado->document_number = $datos['document_number'] ?? '';
    $empleado->document_type = $datos['document_type'] ?? 'CC';
    $empleado->document_expedition_date = $datos['document_expedition_date'] ?? '';
    $empleado->account_type_id = $datos['account_type_id'] ?? 0;
    $empleado->account_number = $datos['account_number'] ?? '';
    $empleado->bank_id = $datos['bank_id'] ?? 0;
    $empleado->address = $datos['address'] ?? '';
    $empleado->phone = $datos['phone'] ?? '';
    $empleado->email = $datos['email'] ?? '';
    $empleado->disability_id = $datos['disability_id'] ?? null;
    $empleado->nationality_id = $datos['nationality_id'] ?? 0;
    $empleado->blood_type_id = $datos['blood_type_id'] ?? 0;
    $empleado->marital_status_id = $datos['marital_status_id'] ?? 0;
    $empleado->profession_id = $datos['profession_id'] ?? 0;
    $empleado->description = $datos['description'] ?? '';
    $empleado->status = 'activo';

    // Validar campos obligatorios
    if (empty($empleado->first_name) || empty($empleado->last_name) || empty($empleado->document_number) || 
        empty($empleado->account_number) || empty($empleado->bank_id) || empty($empleado->address)) {
        echo json_encode(['success' => false, 'message' => 'Faltan campos requeridos']);
        return;
    }

    $resultado = $empleado->crear();
    echo json_encode($resultado);
    $conexion->close();
}

/**
 * Actualizar empleado existente
 */
function actualizarEmpleado() {
    include '../config/conexion.php';

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        return;
    }

    $datos = json_decode(file_get_contents("php://input"), true);

    $empleado = new Empleado($conexion);
    $empleado->id = $datos['id'] ?? 0;
    $empleado->first_name = $datos['first_name'] ?? '';
    $empleado->last_name = $datos['last_name'] ?? '';
    $empleado->document_number = $datos['document_number'] ?? '';
    $empleado->document_type = $datos['document_type'] ?? 'CC';
    $empleado->document_expedition_date = $datos['document_expedition_date'] ?? '';
    $empleado->account_type_id = $datos['account_type_id'] ?? 0;
    $empleado->account_number = $datos['account_number'] ?? '';
    $empleado->bank_id = $datos['bank_id'] ?? 0;
    $empleado->address = $datos['address'] ?? '';
    $empleado->phone = $datos['phone'] ?? '';
    $empleado->email = $datos['email'] ?? '';
    $empleado->disability_id = $datos['disability_id'] ?? null;
    $empleado->nationality_id = $datos['nationality_id'] ?? 0;
    $empleado->blood_type_id = $datos['blood_type_id'] ?? 0;
    $empleado->marital_status_id = $datos['marital_status_id'] ?? 0;
    $empleado->profession_id = $datos['profession_id'] ?? 0;
    $empleado->description = $datos['description'] ?? '';
    $empleado->status = $datos['status'] ?? 'activo';

    if (empty($empleado->id)) {
        echo json_encode(['success' => false, 'message' => 'ID de empleado no válido']);
        return;
    }

    $resultado = $empleado->actualizar();
    echo json_encode($resultado);
    $conexion->close();
}

/**
 * Eliminar empleado
 */
function eliminarEmpleado() {
    include '../config/conexion.php';

    $id = $_GET['id'] ?? 0;

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
        return;
    }

    $empleado = new Empleado($conexion);
    $resultado = $empleado->eliminar($id);
    echo json_encode($resultado);
    $conexion->close();
}

/**
 * Obtener empleado por ID
 */
function obtenerEmpleadoPorId() {
    include '../config/conexion.php';

    $id = $_GET['id'] ?? 0;

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
        return;
    }

    $empleado = new Empleado($conexion);
    $resultado = $empleado->obtenerPorId($id);
    echo json_encode($resultado ?? ['success' => false, 'message' => 'Empleado no encontrado']);
    $conexion->close();
}

/**
 * Obtener catálogos de datos para selectores
 */
function obtenerCatalogos() {
    include '../config/conexion.php';

    $empleado = new Empleado($conexion);
    $catalogos = $empleado->obtenerCatalogos();
    echo json_encode($catalogos);
    $conexion->close();
}

?>
