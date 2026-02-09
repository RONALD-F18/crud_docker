# CRUD Empleados

Sistema simple de gestión de empleados con PHP, MySQL y Docker.

## Requisitos

- Docker
- Docker Compose

## Cómo ejecutar

```bash
docker compose up -d
```

Accede a: http://localhost:8000

## Usuarios y credenciales

**Base de datos:**
- Host: db (o localhost:3306)
- Usuario: usuario_app
- Contraseña: pass123
- Base de datos: crud_empleados

**phpMyAdmin:**
- Acceso: http://localhost:8080
- Usuario: root
- Contraseña: root123

## Archivos principales

- `src/public/` - HTML, CSS, JavaScript
- `src/models/` - Clases de datos
- `src/controllers/` - Lógica de negocio
- `src/config/` - Conexión a BD
- `database.sql` - Esquema inicial
- `docker-compose.yml` - Configuración de contenedores
