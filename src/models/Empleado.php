<?php

class Empleado {
    private $conexion;
    private $tabla = 'empleados';
    
    // Propiedades del empleado
    public $id;
    public $first_name;
    public $last_name;
    public $document_number;
    public $document_type;
    public $document_expedition_date;
    public $account_type_id;
    public $account_number;
    public $bank_id;
    public $address;
    public $phone;
    public $email;
    public $disability_id;
    public $nationality_id;
    public $blood_type_id;
    public $marital_status_id;
    public $profession_id;
    public $description;
    public $status;

    // Constructor: recibe conexión
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function crear() {
        $query = "INSERT INTO " . $this->tabla . "
              (nombre, apellido, numero_documento, tipo_documento, fecha_expedicion_documento, 
               tipo_cuenta_id, numero_cuenta, banco_id, direccion, telefono, correo,
               discapacidad_id, nacionalidad_id, tipo_sangre_id, 
               estado_civil_id, profesion_id, descripcion, estado)
              VALUES
              (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($query);
        
        if (!$stmt) {
            return ['success' => false, 'message' => 'Error en la preparación: ' . $this->conexion->error];
        }

        $stmt->bind_param(
            "sssssisssssiiiiiss",
            $this->first_name,
            $this->last_name,
            $this->document_number,
            $this->document_type,
            $this->document_expedition_date,
            $this->account_type_id,
            $this->account_number,
            $this->bank_id,
            $this->address,
            $this->phone,
            $this->email,
            $this->disability_id,
            $this->nationality_id,
            $this->blood_type_id,
            $this->marital_status_id,
            $this->profession_id,
            $this->description,
            $this->status
        );

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Empleado creado correctamente', 'id' => $this->conexion->insert_id];
        } else {
            return ['success' => false, 'message' => 'Error: ' . $stmt->error];
        }
    }

    public function obtenerTodos() {
                $query = "SELECT 
                                        e.id,
                                        CONCAT(e.nombre, ' ', e.apellido) AS full_name,
                                        e.nombre AS first_name,
                                        e.apellido AS last_name,
                                        e.numero_documento AS document_number,
                                        e.tipo_documento AS document_type,
                                        e.fecha_expedicion_documento AS document_expedition_date,
                                        e.numero_cuenta AS account_number,
                                        e.direccion AS address,
                                        e.telefono AS phone,
                                        e.correo AS email,
                                        e.descripcion AS description,
                                        e.estado AS status,
                                        e.created_at,
                                        tc.name AS account_type,
                                        ba.name AS bank,
                                        d.name AS disability,
                                        n.name AS nationality,
                                        ts.name AS blood_type,
                                        ec.name AS marital_status,
                                        pr.name AS profession
                                    FROM " . $this->tabla . " e
                                    INNER JOIN tipos_cuenta tc ON e.tipo_cuenta_id = tc.id
                                    INNER JOIN bancos ba ON e.banco_id = ba.id
                                    LEFT JOIN discapacidades d ON e.discapacidad_id = d.id
                                    INNER JOIN nacionalidades n ON e.nacionalidad_id = n.id
                                    INNER JOIN tipos_sangre ts ON e.tipo_sangre_id = ts.id
                                    INNER JOIN estado_civil ec ON e.estado_civil_id = ec.id
                                    INNER JOIN profesiones pr ON e.profesion_id = pr.id
                                    WHERE e.estado = 'activo'
                                    ORDER BY e.created_at DESC";

        $resultado = $this->conexion->query($query);

        if (!$resultado) {
            return [];
        }

        $empleados = [];
        while ($fila = $resultado->fetch_assoc()) {
            $empleados[] = $fila;
        }

        return $empleados;
    }

    /**
     * OBTENER EMPLEADO POR ID
     */
    public function obtenerPorId($id) {
                $query = "SELECT 
                                        e.id,
                                        e.nombre AS first_name,
                                        e.apellido AS last_name,
                                        e.numero_documento AS document_number,
                                        e.tipo_documento AS document_type,
                                        e.fecha_expedicion_documento AS document_expedition_date,
                                        e.tipo_cuenta_id AS account_type_id,
                                        e.numero_cuenta AS account_number,
                                        e.banco_id AS bank_id,
                                        e.direccion AS address,
                                        e.telefono AS phone,
                                        e.correo AS email,
                                        e.discapacidad_id AS disability_id,
                                        e.nacionalidad_id AS nationality_id,
                                        e.tipo_sangre_id AS blood_type_id,
                                        e.estado_civil_id AS marital_status_id,
                                        e.profesion_id AS profession_id,
                                        e.descripcion AS description,
                                        e.estado AS status,
                                        tc.name AS account_type,
                                        ba.name AS bank,
                                        d.name AS disability,
                                        n.name AS nationality,
                                        ts.name AS blood_type,
                                        ec.name AS marital_status,
                                        pr.name AS profession
                                    FROM " . $this->tabla . " e
                                    INNER JOIN tipos_cuenta tc ON e.tipo_cuenta_id = tc.id
                                    INNER JOIN bancos ba ON e.banco_id = ba.id
                                    LEFT JOIN discapacidades d ON e.discapacidad_id = d.id
                                    INNER JOIN nacionalidades n ON e.nacionalidad_id = n.id
                                    INNER JOIN tipos_sangre ts ON e.tipo_sangre_id = ts.id
                                    INNER JOIN estado_civil ec ON e.estado_civil_id = ec.id
                                    INNER JOIN profesiones pr ON e.profesion_id = pr.id
                                    WHERE e.id = ?";

        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        return $resultado->fetch_assoc();
    }

    /**
     * ACTUALIZAR EMPLEADO
     */
    public function actualizar() {
        $query = "UPDATE " . $this->tabla . "
                  SET nombre = ?,
                      apellido = ?,
                      numero_documento = ?,
                      tipo_documento = ?,
                      fecha_expedicion_documento = ?,
                      tipo_cuenta_id = ?,
                      numero_cuenta = ?,
                      banco_id = ?,
                      direccion = ?,
                      telefono = ?,
                      correo = ?,
                      discapacidad_id = ?,
                      nacionalidad_id = ?,
                      tipo_sangre_id = ?,
                      estado_civil_id = ?,
                      profesion_id = ?,
                      descripcion = ?,
                      estado = ?
                  WHERE id = ?";

        $stmt = $this->conexion->prepare($query);

        if (!$stmt) {
            return ['success' => false, 'message' => 'Error en la preparación: ' . $this->conexion->error];
        }

        $stmt->bind_param(
            "sssssisssssiiiiissi",
            $this->first_name,
            $this->last_name,
            $this->document_number,
            $this->document_type,
            $this->document_expedition_date,
            $this->account_type_id,
            $this->account_number,
            $this->bank_id,
            $this->address,
            $this->phone,
            $this->email,
            $this->disability_id,
            $this->nationality_id,
            $this->blood_type_id,
            $this->marital_status_id,
            $this->profession_id,
            $this->description,
            $this->status,
            $this->id
        );

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Empleado actualizado correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error: ' . $stmt->error];
        }
    }

    public function eliminar($id) {
        $query = "UPDATE " . $this->tabla . " SET estado = 'inactivo' WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Empleado eliminado correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error: ' . $stmt->error];
        }
    }

    /**
     * OBTENER CATÁLOGOS (selectores)
     */
    public function obtenerCatalogos() {
        $catalogos = [];

        // Tipos de cuenta
        $resultado = $this->conexion->query("SELECT id, name FROM tipos_cuenta");
        $catalogos['account_types'] = $resultado->fetch_all(MYSQLI_ASSOC);

        $resultado = $this->conexion->query("SELECT id, name FROM bancos");
        $catalogos['banks'] = $resultado->fetch_all(MYSQLI_ASSOC);

        $resultado = $this->conexion->query("SELECT id, name FROM discapacidades");
        $catalogos['disabilities'] = $resultado->fetch_all(MYSQLI_ASSOC);

        $resultado = $this->conexion->query("SELECT id, name FROM nacionalidades");
        $catalogos['nationalities'] = $resultado->fetch_all(MYSQLI_ASSOC);

        $resultado = $this->conexion->query("SELECT id, name FROM tipos_sangre");
        $catalogos['blood_types'] = $resultado->fetch_all(MYSQLI_ASSOC);

        $resultado = $this->conexion->query("SELECT id, name FROM estado_civil");
        $catalogos['marital_status'] = $resultado->fetch_all(MYSQLI_ASSOC);

        $resultado = $this->conexion->query("SELECT id, name FROM profesiones");
        $catalogos['professions'] = $resultado->fetch_all(MYSQLI_ASSOC);

        return $catalogos;
    }
}

?>
