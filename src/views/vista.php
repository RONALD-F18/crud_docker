<?php ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Empleados - Sistema Moderno</title>
    <link rel="stylesheet" href="public/style/style.css">
</head>
<body>
    <div class="container">
        <!-- Encabezado -->
        <header class="header">
            <div class="header-content">
                <h1>👥 Módulo de Empleados</h1>
                <p>Sistema de gestión de empleados - CRUD Simple</p>
            </div>
            <button class="btn btn-primary" id="btnNuevoEmpleado">+ Nuevo Empleado</button>
        </header>

        <!-- Tabla de Empleados -->
        <section class="tabla-seccion">
            <h2>📋 Empleados Registrados</h2>
            <div id="tablaContenedor" class="tabla-contenedor">
                <p class="loading">Cargando empleados...</p>
            </div>
        </section>
    </div>

    <!-- MODAL: Formulario de Empleado -->
    <div id="modalEmpleado" class="modal hidden">
        <div class="modal-dialog">
            <div class="modal-header">
                <h2 id="tituloModal">Nuevo Empleado</h2>
                <button class="btn-close" id="btnCerrarModal">&times;</button>
            </div>

            <form id="formEmpleado" class="modal-form">
                <input type="hidden" id="idEmpleado" name="id">

                <!-- Fila 1: Nombre y Apellido -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre *</label>
                        <input type="text" id="nombre" name="first_name" 
                               placeholder="Juan" required>
                        <span class="error-msg" id="errorNombre"></span>
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido *</label>
                        <input type="text" id="apellido" name="last_name" 
                               placeholder="García López" required>
                        <span class="error-msg" id="errorApellido"></span>
                    </div>
                </div>

                <!-- Fila 2: Documento y Tipo -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="numeroDocumento">Número de Documento *</label>
                        <input type="text" id="numeroDocumento" name="document_number" 
                               placeholder="1234567890" required>
                        <span class="error-msg" id="errorDocumento"></span>
                    </div>
                    <div class="form-group">
                        <label for="tipoDocumento">Tipo de Documento</label>
                        <select id="tipoDocumento" name="document_type">
                            <option value="CC">Cédula Ciudadanía</option>
                            <option value="TI">Tarjeta Identidad</option>
                            <option value="CE">Cédula Extranjería</option>
                            <option value="PASSPORT">Pasaporte</option>
                        </select>
                    </div>
                </div>

                <!-- Fila 3: Fecha Expedición y Cta. Bancaria -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="fechaExpedicion">Fecha de Expedición *</label>
                        <input type="date" id="fechaExpedicion" name="document_expedition_date" required>
                    </div>
                    <div class="form-group">
                        <label for="tipoCuenta">Tipo de Cuenta *</label>
                        <select id="tipoCuenta" name="account_type_id" required>
                            <option value="">-- Seleccionar --</option>
                        </select>
                    </div>
                </div>

                <!-- Fila 4: Número Cuenta y Banco -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="numeroCuenta">Número de Cuenta *</label>
                        <input type="text" id="numeroCuenta" name="account_number" 
                               placeholder="0123456789" required>
                        <span class="error-msg" id="errorCuenta"></span>
                    </div>
                    <div class="form-group">
                        <label for="banco">Banco *</label>
                        <select id="banco" name="bank_id" required>
                            <option value="">-- Seleccionar --</option>
                        </select>
                    </div>
                </div>

                <!-- Fila 5: Dirección -->
                <div class="form-group">
                    <label for="direccion">Dirección *</label>
                    <input type="text" id="direccion" name="address" 
                           placeholder="Calle 123 #45-67" required>
                </div>

                <!-- Fila 6: Teléfono y Email -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" name="phone" 
                               placeholder="3105551234">
                        <span class="error-msg" id="errorTelefono"></span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" 
                               placeholder="correo@example.com">
                        <span class="error-msg" id="errorEmail"></span>
                    </div>
                </div>

                <!-- Fila 7: Discapacidad y Nacionalidad -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="discapacidad">Discapacidad</label>
                        <select id="discapacidad" name="disability_id">
                            <option value="">-- Seleccionar --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nacionalidad">Nacionalidad *</label>
                        <select id="nacionalidad" name="nationality_id" required>
                            <option value="">-- Seleccionar --</option>
                        </select>
                    </div>
                </div>

                <!-- Fila 8: RH y Estado Civil -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="rh">Grupo Sanguíneo (RH) *</label>
                        <select id="rh" name="blood_type_id" required>
                            <option value="">-- Seleccionar --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="estatusCivil">Estado Civil *</label>
                        <select id="estatusCivil" name="marital_status_id" required>
                            <option value="">-- Seleccionar --</option>
                        </select>
                    </div>
                </div>

                <!-- Fila 9: Profesión -->
                <div class="form-group">
                    <label for="profesion">Profesión *</label>
                    <select id="profesion" name="profession_id" required>
                        <option value="">-- Seleccionar --</option>
                    </select>
                </div>

                <!-- Fila 10: Descripción -->
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="description" 
                              placeholder="Notas adicionales..." rows="3"></textarea>
                </div>

                <!-- Botones -->
                <div class="form-buttons">
                    <button type="reset" class="btn btn-secondary">Limpiar</button>
                    <button type="submit" class="btn btn-success">💾 Guardar Empleado</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Ver Detalles del Empleado -->
    <div id="modalDetalles" class="modal hidden">
        <div class="modal-dialog modal-lg">
            <div class="modal-header">
                <h2>👁️ Detalles del Empleado</h2>
                <button class="btn-close" id="btnCerrarDetalles">&times;</button>
            </div>

            <div id="detallesContenido" class="detalles-contenido">
                <!-- Se llenará dinámicamente -->
            </div>

            <div class="detalles-botones">
                <button class="btn btn-primary" id="btnEditarDetalles">✏️ Editar</button>
                <button class="btn btn-secondary" id="btnCerrarDetallesBtn">Cerrar</button>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script src="public/js/app.js"></script>
</body>
</html>
