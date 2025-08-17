# API Endpoints - Xenotech

## Información General

- **Base URL**: `http://localhost:8000/api`
- **Autenticación**: Bearer Token (Laravel Sanctum)
- **Content-Type**: `application/json`
- **Formato de respuesta**: JSON

##  Autenticación

### Obtener Token de Acceso

Para acceder a los endpoints protegidos, primero debes obtener un token de autenticación.

---

## Autenticación

### POST /api/register

Registra un nuevo usuario en el sistema.

**URL**: `POST /api/register`

**Headers**:
```
Content-Type: application/json
```

**Body**:
```json
{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "secret123",
    "password_confirmation": "secret123",
    "company_id": 1
}
```

**Respuesta exitosa** (201):
```json
{
    "message": "Usuario registrado",
    "user": {
        "id": 1,
        "name": "Juan Pérez",
        "email": "juan@example.com",
        "role": "user",
        "company_id": 1,
        "created_at": "2025-08-16T20:00:00.000000Z",
        "updated_at": "2025-08-16T20:00:00.000000Z"
    },
    "token": "1|abc123def456..."
}
```

**Errores posibles**:
- `422`: Validación fallida (email duplicado, campos requeridos, etc.)

---

### POST /api/login

Inicia sesión y obtiene un token de acceso.

**URL**: `POST /api/login`

**Headers**:
```
Content-Type: application/json
```

**Body**:
```json
{
    "email": "juan@example.com",
    "password": "secret123"
}
```

**Respuesta exitosa** (200):
```json
{
    "message": "Login exitoso",
    "user": {
        "id": 1,
        "name": "Juan Pérez",
        "email": "juan@example.com",
        "role": "user",
        "company_id": 1
    },
    "token": "1|abc123def456..."
}
```

**Errores posibles**:
- `422`: Credenciales incorrectas

---

### POST /api/logout

Cierra la sesión del usuario actual.

**URL**: `POST /api/logout`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Respuesta exitosa** (200):
```json
{
    "message": "Sesión cerrada"
}
```

---

### GET /api/me

Obtiene la información del usuario autenticado.

**URL**: `GET /api/me`

**Headers**:
```
Authorization: Bearer {token}
```

**Respuesta exitosa** (200):
```json
{
    "user": {
        "id": 1,
        "name": "Juan Pérez",
        "email": "juan@example.com",
        "role": "user",
        "company_id": 1,
        "customer_type": "regular",
        "created_by_name": "Admin",
        "updated_by_name": "Admin"
    }
}
```

---

## Compañias

### GET /api/companies

Lista todas las empresas con paginación.

**URL**: `GET /api/companies`

**Headers**:
```
Authorization: Bearer {token}
```

**Query Parameters**:
- `page` (opcional): Número de página (default: 1)
- `per_page` (opcional): Elementos por página (default: 15)
- `customer_type` (opcional): Filtrar por tipo de cliente (regular, premium, vip)
- `is_active` (opcional): Filtrar por estado activo (true/false)
- `search` (opcional): Buscar por nombre o email

**Ejemplo**:
```
GET /api/companies?page=1&per_page=10&customer_type=premium&search=tech
```

**Respuesta exitosa** (200):
```json
{
    "data": [
        {
            "id": 1,
            "name": "Tech Solutions",
            "email": "info@techsolutions.com",
            "phone": "+1234567890",
            "address": "123 Tech Street",
            "customer_type": "premium",
            "is_active": true,
            "created_at": "2025-08-16T20:00:00.000000Z",
            "updated_at": "2025-08-16T20:00:00.000000Z",
            "created_by": {
                "id": 1,
                "name": "Admin"
            },
            "updated_by": {
                "id": 1,
                "name": "Admin"
            }
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 15,
        "total": 1
    }
}
```

---

### POST /api/companies

Crea una nueva empresa.

**URL**: `POST /api/companies`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body**:
```json
{
    "name": "Nueva Empresa",
    "email": "info@nuevaempresa.com",
    "phone": "+1234567890",
    "address": "456 Business Ave",
    "customer_type": "regular",
    "is_active": true
}
```

**Respuesta exitosa** (201):
```json
{
    "message": "Company created successfully",
    "data": {
        "id": 2,
        "name": "Nueva Empresa",
        "email": "info@nuevaempresa.com",
        "phone": "+1234567890",
        "address": "456 Business Ave",
        "customer_type": "regular",
        "is_active": true,
        "created_at": "2025-08-16T20:00:00.000000Z",
        "updated_at": "2025-08-16T20:00:00.000000Z"
    }
}
```

---

### GET /api/companies/{id}

Obtiene una empresa específica.

**URL**: `GET /api/companies/{id}`

**Headers**:
```
Authorization: Bearer {token}
```

**Respuesta exitosa** (200):
```json
{
    "data": {
        "id": 1,
        "name": "Tech Solutions",
        "email": "info@techsolutions.com",
        "phone": "+1234567890",
        "address": "123 Tech Street",
        "customer_type": "premium",
        "is_active": true,
        "created_at": "2025-08-16T20:00:00.000000Z",
        "updated_at": "2025-08-16T20:00:00.000000Z",
        "users": [
            {
                "id": 1,
                "name": "Juan Pérez",
                "email": "juan@example.com"
            }
        ]
    }
}
```

---

### PUT /api/companies/{id}

Actualiza una empresa existente.

**URL**: `PUT /api/companies/{id}`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body**:
```json
{
    "name": "Tech Solutions Updated",
    "email": "newemail@techsolutions.com",
    "customer_type": "vip"
}
```

**Respuesta exitosa** (200):
```json
{
    "message": "Company updated successfully",
    "data": {
        "id": 1,
        "name": "Tech Solutions Updated",
        "email": "newemail@techsolutions.com",
        "customer_type": "vip",
        "updated_at": "2025-08-16T20:00:00.000000Z"
    }
}
```

---

### DELETE /api/companies/{id}

Elimina una empresa (solo si no tiene usuarios asociados).

**URL**: `DELETE /api/companies/{id}`

**Headers**:
```
Authorization: Bearer {token}
```

**Respuesta exitosa** (200):
```json
{
    "message": "Company deleted successfully"
}
```

**Errores posibles**:
- `422`: No se puede eliminar empresa con usuarios asociados

---

## Pedidos

### GET /api/orders

Lista todos los pedidos de la empresa del usuario autenticado.

**URL**: `GET /api/orders`

**Headers**:
```
Authorization: Bearer {token}
```

**Query Parameters**:
- `page` (opcional): Número de página (default: 1)
- `per_page` (opcional): Elementos por página (default: 15)

**Respuesta exitosa** (200):
```json
{
    "data": [
        {
            "id": 1,
            "customer_name": "María García",
            "total_amount": "999.99",
            "status": "pending",
            "created_at": "2025-08-16T20:00:00.000000Z",
            "updated_at": "2025-08-16T20:00:00.000000Z",
            "user": {
                "id": 1,
                "name": "Juan Pérez"
            },
            "items": [
                {
                    "id": 1,
                    "product_name": "Laptop",
                    "quantity": 1,
                    "price": "999.99",
                    "subtotal": "999.99"
                }
            ]
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 15,
        "total": 1
    }
}
```

---

### POST /api/orders

Crea un nuevo pedido.

**URL**: `POST /api/orders`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body**:
```json
{
    "customer_name": "María García",
    "items": [
        {
            "product_name": "Laptop",
            "quantity": 1,
            "price": 999.99
        },
        {
            "product_name": "Mouse",
            "quantity": 2,
            "price": 25.50
        }
    ]
}
```

**Respuesta exitosa** (201):
```json
{
    "message": "Order created successfully",
    "data": {
        "id": 1,
        "customer_name": "María García",
        "total_amount": "1050.99",
        "status": "pending",
        "created_at": "2025-08-16T20:00:00.000000Z",
        "updated_at": "2025-08-16T20:00:00.000000Z",
        "items": [
            {
                "id": 1,
                "product_name": "Laptop",
                "quantity": 1,
                "price": "999.99",
                "subtotal": "999.99"
            },
            {
                "id": 2,
                "product_name": "Mouse",
                "quantity": 2,
                "price": "25.50",
                "subtotal": "51.00"
            }
        ]
    }
}
```

**Nota**: El total se calcula automáticamente aplicando los descuentos configurados (descuento de lunes y descuento aleatorio).

---

### GET /api/orders/{id}

Obtiene un pedido específico con sus transiciones disponibles.

**URL**: `GET /api/orders/{id}`

**Headers**:
```
Authorization: Bearer {token}
```

**Respuesta exitosa** (200):
```json
{
    "data": {
        "id": 1,
        "customer_name": "María García",
        "total_amount": "1050.99",
        "status": "pending",
        "created_at": "2025-08-16T20:00:00.000000Z",
        "updated_at": "2025-08-16T20:00:00.000000Z",
        "user": {
            "id": 1,
            "name": "Juan Pérez"
        },
        "items": [
            {
                "id": 1,
                "product_name": "Laptop",
                "quantity": 1,
                "price": "999.99",
                "subtotal": "999.99"
            }
        ]
    },
    "available_transitions": ["processing", "cancelled"]
}
```

**Errores posibles**:
- `404`: Pedido no encontrado

---

### PUT /api/orders/{id}

Actualiza un pedido existente (principalmente para cambiar estado).

**URL**: `PUT /api/orders/{id}`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body** (cambiar estado):
```json
{
    "status": "processing"
}
```

**Body** (actualizar otros campos):
```json
{
    "customer_name": "María García Updated",
    "status": "processing"
}
```

**Respuesta exitosa** (200):
```json
{
    "message": "Order updated successfully",
    "data": {
        "id": 1,
        "customer_name": "María García Updated",
        "total_amount": "1050.99",
        "status": "processing",
        "updated_at": "2025-08-16T20:00:00.000000Z"
    },
    "available_transitions": ["shipped", "cancelled"]
}
```

**Errores posibles**:
- `400`: Transición de estado inválida
- `404`: Pedido no encontrado

---

## Estados de Pedidos

### Transiciones Válidas

| Estado Actual | Estados Permitidos |
|---------------|-------------------|
| `pending`     | `processing`, `cancelled` |
| `processing`  | `shipped`, `cancelled` |
| `shipped`     | `delivered`, `cancelled` |
| `delivered`   | (estado final) |
| `cancelled`   | (estado final) |

### Ejemplo de Flujo de Estados

```bash
# 1. Crear pedido (estado: pending)
POST /api/orders

# 2. Cambiar a processing
PUT /api/orders/1
{
    "status": "processing"
}

# 3. Cambiar a shipped
PUT /api/orders/1
{
    "status": "shipped"
}

# 4. Cambiar a delivered
PUT /api/orders/1
{
    "status": "delivered"
}
```

---

## 🎯 Tipos de Cliente

### Estrategias de Notificación

| Tipo de Cliente | Notificación     | Descripción            |
|-----------------|------------------|------------------------|
| `regular`       | Sin notificación | Cliente básico         |
| `premium`       | Email            | Cliente con beneficios |
| `vip`           | WhatsApp         | Cliente de alto valor  |

### Ejemplo de Respuesta con Notificación

Cuando se actualiza el estado de un pedido, se envía automáticamente una notificación según el tipo de cliente:

```json
{
    "message": "Order updated successfully",
    "data": {
        "id": 1,
        "status": "processing"
    },
    "notification_sent": true,
    "notification_method": "email"
}
```

---

## Descuentos Automáticos

### Tipos de Descuento

1. **Descuento de Lunes** (10%): Se aplica automáticamente a todos los pedidos realizados los lunes
2. **Descuento Aleatorio** (1-3%): Se aplica aleatoriamente de lunes a jueves

### Ejemplo de Cálculo

```json
{
    "subtotal": "1000.00",
    "monday_discount": "100.00",      // 10% si es lunes
    "random_discount": "25.00",       // 2.5% aleatorio
    "total_amount": "875.00"
}
```

---

## Códigos de Error

### Errores Comunes

| Código | Descripción |
|--------|-------------|
| `400`  | Bad Request - Datos inválidos |
| `401`  | Unauthorized - Token inválido o expirado |
| `403`  | Forbidden - Sin permisos |
| `404`  | Not Found - Recurso no encontrado |
| `422`  | Unprocessable Entity - Validación fallida |
| `500`  | Internal Server Error - Error del servidor |

### Ejemplo de Error de Validación

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```

---

## Ejemplos de Uso Completo

### Flujo Completo: Crear y Gestionar un Pedido

```bash
# 1. Registrar usuario
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "secret123",
    "password_confirmation": "secret123",
    "company_id": 1
  }'

# 2. Iniciar sesión
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "juan@example.com",
    "password": "secret123"
  }'

# Guardar el token de la respuesta
TOKEN="1|abc123def456..."

# 3. Crear pedido
curl -X POST http://localhost:8000/api/orders \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "customer_name": "María García",
    "items": [
      {
        "product_name": "Laptop",
        "quantity": 1,
        "price": 999.99
      }
    ]
  }'

# 4. Cambiar estado a processing
curl -X PUT http://localhost:8000/api/orders/1 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "status": "processing"
  }'

# 5. Cambiar estado a shipped
curl -X PUT http://localhost:8000/api/orders/1 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "status": "shipped"
  }'

# 6. Cambiar estado a delivered
curl -X PUT http://localhost:8000/api/orders/1 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "status": "delivered"
  }'
```

---

## Configuración de Webhook

Las notificaciones se envían automáticamente al webhook configurado en el archivo `.env`:

```env
WEBHOOK_NOTIFICATION_URL=https://webhook.site/263d24fd-e9c9-485f-a981-9a6d0f5c95ec
```

### Formato del Payload del Webhook

```json
{
    "message": "Tu pedido #1 ha cambiado a estado: processing",
    "data": {
        "order_id": 1,
        "user_id": 1,
        "email": "juan@example.com",
        "old_status": "pending",
        "new_status": "processing",
        "total_amount": "999.99"
    },
    "customer_type": "premium",
    "timestamp": "2025-08-16T20:00:00.000Z",
    "notification_method": "email"
}
```

------------------------------------------------------------------------------------------
