# Prueba Técnica Xenotech - Desarrollador Senior

## Descripción General

Esta prueba técnica está diseñada para evaluar las habilidades de un desarrollador senior en PHP. El candidato debe implementar una aplicación que demuestre competencia en el desarrollo de APIs, gestión de bases de datos y aplicación de patrones de diseño.

## Requisitos Técnicos

- **PHP**: Versión 8.0 o superior
- **Framework**: Cualquier framework de preferencia (Laravel, Symfony, Slim, etc.)
- **Base de Datos**: MySQL local
- **Tiempo Estimado**: 2-4 horas

## Ejercicios

### Ejercicio: Sistema de Gestión de Pedidos (4 horas)

#### Descripción
Implementar un sistema básico de gestión de pedidos con diferentes estados y sistema de notificaciones integrado.

#### Requisitos Funcionales

1. **Endpoints CRUD para Pedidos**:
   - `GET /api/orders` - Listar todos los pedidos con paginación
   - `GET /api/orders/{id}` - Obtener un pedido específico
   - `POST /api/orders` - Crear un nuevo pedido
   - `PUT /api/orders/{id}` - Actualizar un pedido y cambiar su estado

2. **Estructura de Base de Datos**:
   - Tabla `orders` con campos: id, customer_name, total_amount, status, created_at, updated_at
   - Tabla `order_items` con campos: id, order_id, product_name, quantity, price
   - Implementar migraciones para crear estas tablas

3. **Patrones de Diseño Requeridos**:
   - **State Machine**: Implementar estados del pedido (PENDING, PROCESSING, SHIPPED, DELIVERED, CANCELLED)
   - **Strategy Pattern**: Diferentes estrategias de notificación según el tipo de cliente
   - **Decorator Pattern**: Agregar funcionalidades adicionales como logging, validación, etc.

#### Especificaciones Técnicas

**State Machine para Estados de Pedido**:
- PENDING → PROCESSING (cuando se confirma el pedido)
- PROCESSING → SHIPPED (cuando se envía)
- SHIPPED → DELIVERED (cuando se entrega)
- Cualquier estado → CANCELLED (cuando se cancela)

**Nota**: Los cambios de estado se realizan únicamente a través del endpoint `PUT /api/orders/{id}` enviando el nuevo estado en el campo `status`.

**Strategy Pattern para Notificaciones**:
- Cliente Regular: Sin notificación
- Cliente Premium: Notificación vía email
- Cliente VIP: Notificación vía WhatsApp

**Nota**: Las notificaciones se envían mediante una petición POST al webhook: `https://webhook.site/263d24fd-e9c9-485f-a981-9a6d0f5c95ec`


**Decorator Pattern**:
- **Oferta Base**: Decorator que aplica un descuento base del 10% a todos los pedidos realizados los días lunes
- **Oferta Aleatoria**: Decorator que aplica un descuento adicional aleatorio entre 1% y 3% realizados todos los días de lunes a jueves
- Ambos decoradores se aplican automáticamente al calcular el total del pedido

## Criterios de Evaluación

### Puntos Técnicos (70%)
- **Endpoints RESTful**: 20 puntos
- **Migraciones de Base de Datos**: 15 puntos
- **State Machine**: 15 puntos
- **Strategy Pattern (Notificaciones)**: 10 puntos
- **Decorator Pattern (Ofertas)**: 10 puntos

### Calidad del Código (30%)
- **Estructura y Organización**: 10 puntos
- **Documentación**: 10 puntos
- **Manejo de Errores**: 10 puntos

## Instrucciones de Entrega

1. **Fork/Clone** este repositorio
2. **Implementar** los ejercicios siguiendo las especificaciones
3. **Documentar** el proceso de instalación y ejecución
4. **Crear** un archivo `SETUP.md` con instrucciones de configuración
5. **Incluir** ejemplos de uso de los endpoints
6. **Hacer commit** de todos los cambios
7. **Enviar** el repositorio o crear un pull request

## Estructura de Archivos Sugerida

```
├── README.md
├── SETUP.md
├── database/
│   ├── migrations/
│   └── seeds/
├── src/
│   ├── Controllers/
│   ├── Models/
│   ├── Services/
│   ├── Patterns/
│   │   ├── State/
│   │   ├── Strategy/
│   │   └── Decorator/
│   └── Database/
├── tests/
└── composer.json
```

## Notas Importantes

- **No es necesario** implementar autenticación compleja
- **Usar** datos de prueba (seeds) para demostrar funcionalidad
- **Documentar** decisiones de diseño importantes
- **Mantener** el código limpio y bien estructurado
- **Incluir** manejo básico de errores y validaciones

## Preguntas Frecuentes

**¿Puedo usar cualquier framework?**
Sí, puedes usar Laravel, Symfony, Slim, o cualquier otro framework de tu preferencia.

**¿Necesito implementar autenticación?**
No es necesario, pero puedes agregarla si consideras que mejora la demostración.

**¿Qué pasa si no completo todos los patrones?**
Se evalúa la calidad de lo implementado. Es mejor hacer pocas cosas bien que muchas mal.

**¿Puedo usar Docker?**
Sí, es recomendable para facilitar la configuración del entorno.

---

**¡Buena suerte! Esperamos ver tu mejor trabajo.**
