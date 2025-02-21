# 📌 API REST - Tienda Online

## 📖 Introducción
Esta es una API REST desarrollada en Laravel con Sanctum para la autenticación. Permite la gestión de tiendas, productos, carritos de compra y órdenes de compra. Además cuenta con implemntación de policies para restrigir el acceso entre vendedores.



## 🔑 Autenticación
La autenticación se maneja con Laravel Sanctum. Se requiere un token para acceder a la mayoría de los endpoints.

| Método | Endpoint       | Descripción |
|--------|--------------|-------------|
| POST   | `/register`  | Registro de usuario |
| POST   | `/login`     | Iniciar sesión y obtener token |
| POST   | `/logout`    | Cerrar sesión (requiere token) |

---

## 🏪 Tiendas
Los vendedores pueden administrar tiendas y consultar información sobre ellas.

| Método | Endpoint | Descripción |
|--------|---------|-------------|
| GET    | `/store/show/` | Listar todas las tiendas |
| GET    | `/store/find/{id}` | Ver detalles de una tienda |
| POST   | `/store/create/` | Crear una nueva tienda (Requiere ser vendedor) |
| PUT    | `/store/update/{id}` | Actualizar tienda (Requiere ser vendedor) |
| DELETE | `/store/delete/{id}` | Eliminar tienda (Requiere ser vendedor) |

🔒 **Nota:** Se usan **policies** para restringir `create`, `update` y `delete` en tiendas.

---

## 🛒 Productos
Los vendedores pueden gestionar productos dentro de sus tiendas.

| Método | Endpoint | Descripción |
|--------|---------|-------------|
| GET    | `/product/show/` | Listar todos los productos |
| GET    | `/product/find/{id}` | Ver detalles de un producto |
| POST   | `/product/create/` | Crear un nuevo producto (Requiere ser vendedor) |
| PUT    | `/product/update/{id}` | Actualizar producto (Requiere ser vendedor) |
| DELETE | `/product/delete/{id}` | Eliminar producto (Requiere ser vendedor) |

🔒 **Nota:** Se usan **policies** para restringir `create`, `update` y `delete` en productos.

---

## 🛍️ Carrito de Compras
Los clientes pueden agregar y quitar productos del carrito.

| Método | Endpoint | Descripción |
|--------|---------|-------------|
| GET    | `/cart/show/` | Ver productos en el carrito |
| POST   | `/cart/add/` | Agregar un producto al carrito |
| POST   | `/cart/remove/` | Quitar un producto del carrito |

---

## 💳 Finalizar Compra
Los clientes pueden realizar el pago de los productos en su carrito.

| Método | Endpoint | Descripción |
|--------|---------|-------------|
| POST   | `/cart/pay/` | Realizar el pago y confirmar la compra |

---

## 📜 Historial de Compras
Los clientes pueden consultar sus órdenes pasadas.

| Método | Endpoint | Descripción |
|--------|---------|-------------|
| GET    | `/orders` | Obtener el historial de compras |

---

## 🛡️ Middleware y Roles
La API usa middleware para restringir el acceso a ciertos endpoints:
- `auth:sanctum` → Requiere autenticación.
- `is_seller` → Solo vendedores pueden gestionar tiendas y productos.
- `is_client` → Solo clientes pueden gestionar carritos y compras.

---

