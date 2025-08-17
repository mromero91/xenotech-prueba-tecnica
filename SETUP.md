
## Instalación Paso a Paso

### Paso 1: Clonar el repositorio

```bash
git clone <repository-url>
cd xenotech-prueba-tecnica
```

### Paso 2: Instalar dependencias de PHP

```bash
composer install --no-dev --optimize-autoloader
```

### Paso 3: Configurar el archivo de entorno

```bash
cp env.example .env
```

Editar el archivo `.env` con tu configuración:

```env
APP_NAME="Xenotech"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=xenotech_db
DB_USERNAME=root
DB_PASSWORD=secret123

SESSION_DRIVER=database
SESSION_LIFETIME=120

CACHE_STORE=database

# Webhook para notificaciones
WEBHOOK_NOTIFICATION_URL=https://webhook.site/263d24fd-e9c9-485f-a981-9a6d0f5c95ec
```

### Paso 4: Generar clave de aplicación

```bash
php artisan key:generate
```

### Paso 5: Configurar base de datos

1. Crear la base de datos:
```sql
CREATE DATABASE xenotech_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Ejecutar migraciones:
```bash
php artisan migrate
```

3. Ejecutar migraciones:
```bash
php artisan migrate
```

### Paso 6: Poblar la base de datos con datos de prueba

```bash
php artisan db:seed
```

### Paso 7: Configurar permisos (solo Linux/Mac)

```bash
chmod -R 775 storage bootstrap/cache
chown -R $USER:www-data storage bootstrap/cache
```

### Paso 8: Iniciar el servidor

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

##  Verificación de la Instalación

### 1. Verificar que el servidor esté funcionando

```bash
curl http://localhost:8000
```

************Deberías ver la página de bienvenida de la API.************

### 2. Endpoint de registro

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "company_id": 1
  }'
```
