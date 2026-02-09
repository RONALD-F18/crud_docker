const API_URL = 'controllers/EmpleadoController.php';

let catalogos = {};
let empleados = [];
let editandoId = null;
let detalleEmpleadoId = null;

// Elementos del DOM
const modal = document.getElementById('modalEmpleado');
const modalDetalles = document.getElementById('modalDetalles');
const btnNuevoEmpleado = document.getElementById('btnNuevoEmpleado');
const btnCerrarModal = document.getElementById('btnCerrarModal');
const btnCerrarDetalles = document.getElementById('btnCerrarDetalles');
const btnEditarDetalles = document.getElementById('btnEditarDetalles');
const formEmpleado = document.getElementById('formEmpleado');
const tablaContenedor = document.getElementById('tablaContenedor');
const detallesContenido = document.getElementById('detallesContenido');

// Inicializar
document.addEventListener('DOMContentLoaded', () => {
    cargarCatalogos();
    cargarEmpleados();
    configurarEventos();
});

function configurarEventos() {
    btnNuevoEmpleado.addEventListener('click', abrirModalNuevo);
    btnCerrarModal.addEventListener('click', () => cerrarModal());
    btnCerrarDetalles.addEventListener('click', () => cerrarModalDetalles());
    btnEditarDetalles.addEventListener('click', editarDesdeDetalles);
    
    modal.addEventListener('click', (e) => {
        if (e.target === modal) cerrarModal();
    });
    
    modalDetalles.addEventListener('click', (e) => {
        if (e.target === modalDetalles) cerrarModalDetalles();
    });
    
    formEmpleado.addEventListener('submit', guardarEmpleado);
}

function cargarCatalogos() {
    fetch(`${API_URL}?accion=catalogos`)
        .then(r => r.json())
        .then(datos => {
            catalogos = datos;
            llenarSelectores();
        })
        .catch(e => {
            console.error('Error catálogos:', e);
            mostrarAlerta('Error al cargar catálogos', 'error');
        });
}

function llenarSelectores() {
    const mapeo = {
        'tipoCuenta': catalogos.account_types || [],
        'banco': catalogos.banks || [],
        'discapacidad': catalogos.disabilities || [],
        'nacionalidad': catalogos.nationalities || [],
        'rh': catalogos.blood_types || [],
        'estatusCivil': catalogos.marital_status || [],
        'profesion': catalogos.professions || []
    };

    for (const [id, items] of Object.entries(mapeo)) {
        const select = document.getElementById(id);
        if (!select) continue;

        select.innerHTML = '<option value="">-- Seleccionar --</option>';
        items.forEach(item => {
            // Todos los catálogos devuelven campo 'name' (alias en el modelo)
            const nombre = item.name || item.type || item.type_name || item.bank_name || '';
            select.innerHTML += `<option value="${item.id}">${nombre}</option>`;
        });
    }
}

function cargarEmpleados() {
    fetch(`${API_URL}?accion=obtener`)
        .then(r => r.json())
        .then(datos => {
            empleados = datos;
            mostrarTabla(empleados);
        })
        .catch(e => {
            console.error('Error:', e);
            tablaContenedor.innerHTML = '<p class="no-data">Error al cargar empleados</p>';
        });
}

function mostrarTabla(datos) {
    if (!datos || datos.length === 0) {
        tablaContenedor.innerHTML = '<p class="no-data">📭 No hay empleados registrados</p>';
        return;
    }

    let html = `
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Banco</th>
                    <th>Profesión</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
    `;

    datos.forEach(emp => {
        const nombre = `${emp.first_name || ''} ${emp.last_name || ''}`.trim();
        html += `
            <tr>
                <td><strong>${nombre}</strong></td>
                <td>${emp.document_number || ''}</td>
                <td>${emp.bank || ''}</td>
                <td>${emp.profession || ''}</td>
                <td>${emp.phone || 'N/A'}</td>
                <td>
                    <div class="acciones">
                        <button class="btn btn-primary" onclick="verDetalles(${emp.id})" type="button">👁️</button>
                        <button class="btn btn-edit" onclick="editarEmpleado(${emp.id})" type="button">✏️</button>
                        <button class="btn btn-danger" onclick="eliminarEmpleado(${emp.id})" type="button">🗑️</button>
                    </div>
                </td>
            </tr>
        `;
    });

    html += '</tbody></table>';
    tablaContenedor.innerHTML = html;
}

function verDetalles(id) {
    fetch(`${API_URL}?accion=obtenerPorId&id=${id}`)
        .then(r => r.json())
        .then(emp => {
            detalleEmpleadoId = id;
            let html = `
                <div class="details-grid">
                    <div class="detail-item">
                        <div class="detail-label">👤 Nombre</div>
                        <div class="detail-value">${emp.first_name || ''} ${emp.last_name || ''}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">📄 Documento</div>
                        <div class="detail-value">${emp.document_number || ''} (${emp.document_type || ''})</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">📅 Fecha Exp.</div>
                        <div class="detail-value">${emp.document_expedition_date || ''}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">🏦 Banco</div>
                        <div class="detail-value">${emp.bank || ''}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">💳 Tipo Cuenta</div>
                        <div class="detail-value">${emp.account_type || ''}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">🔢 Nº Cuenta</div>
                        <div class="detail-value">${emp.account_number || ''}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">📍 Dirección</div>
                        <div class="detail-value">${emp.address || ''}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">☎️ Teléfono</div>
                        <div class="detail-value">${emp.phone || 'No'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">📧 Email</div>
                        <div class="detail-value">${emp.email || 'No'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">🌍 Nacionalidad</div>
                        <div class="detail-value">${emp.nationality || ''}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">🩸 RH</div>
                        <div class="detail-value">${emp.blood_type || ''}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">💍 Estado Civil</div>
                        <div class="detail-value">${emp.marital_status || ''}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">💼 Profesión</div>
                        <div class="detail-value">${emp.profession || ''}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">♿ Discapacidad</div>
                        <div class="detail-value">${emp.disability || 'Ninguna'}</div>
                    </div>
                    <div class="detail-item detail-full">
                        <div class="detail-label">📝 Descripción</div>
                        <div class="detail-value">${emp.description || 'Sin notas'}</div>
                    </div>
                </div>
            `;
            detallesContenido.innerHTML = html;
            modalDetalles.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(e => {
            console.error('Error:', e);
            mostrarAlerta('Error al cargar detalles', 'error');
        });
}

function cerrarModalDetalles() {
    modalDetalles.classList.add('hidden');
    document.body.style.overflow = 'auto';
    detalleEmpleadoId = null;
}

function editarDesdeDetalles() {
    if (!detalleEmpleadoId) return;
    cerrarModalDetalles();
    editarEmpleado(detalleEmpleadoId);
}

function abrirModalNuevo() {
    editandoId = null;
    formEmpleado.reset();
    document.getElementById('idEmpleado').value = '';
    document.getElementById('tituloModal').textContent = 'Nuevo Empleado';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function editarEmpleado(id) {
    editandoId = id;
    fetch(`${API_URL}?accion=obtenerPorId&id=${id}`)
        .then(r => r.json())
        .then(emp => {
            document.getElementById('idEmpleado').value = emp.id;
            document.getElementById('nombre').value = emp.first_name || '';
            document.getElementById('apellido').value = emp.last_name || '';
            document.getElementById('numeroDocumento').value = emp.document_number || '';
            document.getElementById('tipoDocumento').value = emp.document_type || 'CC';
            document.getElementById('fechaExpedicion').value = emp.document_expedition_date || '';
            document.getElementById('tipoCuenta').value = emp.account_type_id || '';
            document.getElementById('numeroCuenta').value = emp.account_number || '';
            document.getElementById('banco').value = emp.bank_id || '';
            document.getElementById('direccion').value = emp.address || '';
            document.getElementById('telefono').value = emp.phone || '';
            document.getElementById('email').value = emp.email || '';
            document.getElementById('discapacidad').value = emp.disability_id || '';
            document.getElementById('nacionalidad').value = emp.nationality_id || '';
            document.getElementById('rh').value = emp.blood_type_id || '';
            document.getElementById('estatusCivil').value = emp.marital_status_id || '';
            document.getElementById('profesion').value = emp.profession_id || '';
            document.getElementById('descripcion').value = emp.description || '';
            
            document.getElementById('tituloModal').textContent = 'Editar Empleado';
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(e => {
            console.error('Error:', e);
            mostrarAlerta('Error al cargar empleado', 'error');
        });
}

function cerrarModal() {
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    formEmpleado.reset();
    editandoId = null;
}

function guardarEmpleado(e) {
    e.preventDefault();

    const formData = new FormData(formEmpleado);
    const datos = {};
    
    formData.forEach((value, key) => {
        datos[key] = value;
    });

    // Convertir a números
    datos.id = datos.id ? parseInt(datos.id) : null;
    datos.account_type_id = parseInt(datos.account_type_id) || 0;
    datos.bank_id = parseInt(datos.bank_id) || 0;
    datos.disability_id = datos.disability_id ? parseInt(datos.disability_id) : null;
    datos.nationality_id = parseInt(datos.nationality_id) || 0;
    datos.blood_type_id = parseInt(datos.blood_type_id) || 0;
    datos.marital_status_id = parseInt(datos.marital_status_id) || 0;
    datos.profession_id = parseInt(datos.profession_id) || 0;

    const accion = datos.id ? 'actualizar' : 'crear';

    fetch(`${API_URL}?accion=${accion}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            mostrarAlerta('✅ ' + res.message, 'success');
            cerrarModal();
            cargarEmpleados();
        } else {
            mostrarAlerta('❌ ' + res.message, 'error');
        }
    })
    .catch(e => {
        console.error('Error:', e);
        mostrarAlerta('❌ Error al guardar', 'error');
    });
}

function eliminarEmpleado(id) {
    if (!confirm('¿Eliminar este empleado?')) return;

    fetch(`${API_URL}?accion=eliminar&id=${id}`)
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                mostrarAlerta('✅ ' + res.message, 'success');
                cargarEmpleados();
            } else {
                mostrarAlerta('❌ ' + res.message, 'error');
            }
        })
        .catch(e => {
            console.error('Error:', e);
            mostrarAlerta('❌ Error al eliminar', 'error');
        });
}

function mostrarAlerta(msg, tipo) {
    const alerta = document.createElement('div');
    alerta.className = `alert alert-${tipo}`;
    alerta.textContent = msg;
    document.body.insertBefore(alerta, document.body.firstChild);
    alerta.classList.add('show');
    setTimeout(() => alerta.remove(), 5000);
}
