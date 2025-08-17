# Xenotech - Guía de Instalación y Configuración

## Diagrama de la Base de Datos

![Diagrama de Base de Datos](https://mromero-1091.s3.us-east-2.amazonaws.com/DiagramaDB.svg?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Credential=ASIAV26BSBJJHECLVX7L%2F20250817%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20250817T210022Z&X-Amz-Expires=300&X-Amz-Security-Token=IQoJb3JpZ2luX2VjEE0aCXVzLWVhc3QtMiJHMEUCIFUqHa9I2XRlMAawaRSGIorWn9x9q0rgZVuC72Yk6XqkAiEAtto3%2FrDB5VwiSYRyavOdWT9%2BGPTh%2BRRvizLMh8oXSswq4wIIlv%2F%2F%2F%2F%2F%2F%2F%2F%2F%2FARAAGgw0MDE0NDg1MDM4OTAiDLKDPbhUQy4YhuGCyyq3Ak4j0MdZsIwogyTF9EBRnvFomfPZun%2B67sxa1KnNGZfJ6V43e8QKFSmAxzsKpfp%2BIs%2FqnfjNXZ8aKfq3xB6YusRowWg%2B4%2BitYzeS6JHLFelAAQSj1YzMLMUmezj366oomQftaDGdK0wQ8EvYCA7VE%2BFqCX%2BulTZkU%2BWJFi4RrZ8rmI58U3H37XAhbTiup1Ahft5q%2FOimQMYTl2cUUy49NXTEBLVMLFseeatSLsLboh1PlqbCt7ZlX6Zvbb2WnWCa3dvwbLgEgVeUysyQa52KGubeDqUScwToHuEHWHRYXMW1Ks52e9WRxyDdpW6z8GzcprkH3PgqRjuNYQketU%2B7gkMrT2e%2FeSd9FLJQwm%2BR5LuzgzCUm1IjxCHCGC8%2FQJTOrDEr72bke%2B6M03xp4wRQahkXantHyQSFMLqFicUGOq0CQHxEFo%2BcumNeia%2FkaKzTTLkEBA5HI0XWdyS3Dziala83OxYiyw2lANVzz4C219J5ohG9At%2BoZGgFHOf4fhEVTg7kC%2B2HjceTxv9txw%2BsK1RLxNUcE75WasZrdIG3pm%2BIuxXwPMNMMB1V%2BAJyPiDxH1AWluza3HHmNx0%2BU0Sd%2BOt7%2B4g79Ru6YXDDrJJMzpaNyXi5ZkRFfrgKU%2FhU5J3o6v2I%2FIVqsvn%2B%2BQszKa8meQg8kvudia9wJiXaz6ezvYCn3IINuHrEj1WUL9ctKvHs3mCYTg9NzGMSVAKXX3t1%2FvRX7%2FW%2BAqJTc2%2BVQqhAHkX5LZgCGwBcRLLtG2pw3cSAVTbm%2FnALuMYyGog0P0%2FFgOo0px%2FdVOoBLVFC3LvRc5k3xzrCRD7QVdDSx%2BQSXA%3D%3D&X-Amz-Signature=975ab11ef2c2f3eebbeac17bf8bcc53d596c053c6f615436f9d0139853dbb030&X-Amz-SignedHeaders=host&response-content-disposition=inline)

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
