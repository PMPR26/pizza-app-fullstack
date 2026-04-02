# 🍕 Pizza App - Fullstack

Aplicación fullstack para gestión de pedidos de pizzas, desarrollada con:

* ⚙️ Backend: Laravel (API REST)
* 🎨 Frontend: Vue 3 + Vite + Tailwind CSS
* 🗄️ Base de datos: MySQL/PostgreSQL

---

## 📁 Estructura del proyecto

```
pizza-app-fullstack/
├── backend/   # API Laravel
├── frontend/  # App Vue 3
```

---

## 🚀 Backend (Laravel)

### Requisitos

* PHP >= 8.1
* Composer
* MySQL/PostgreSQL

### Instalación

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

Configura tu base de datos en `.env`:

```env
DB_DATABASE=pizza_app
DB_USERNAME=root
DB_PASSWORD=
```

Ejecutar migraciones:

```bash
php artisan migrate --seed
```

Iniciar servidor:

```bash
php artisan serve
```

---

### 🧪 Tests

```bash
./vendor/bin/pest
```

Incluye pruebas para:

* Autenticación (registro/login)
* Menú de pizzas
* Checkout de órdenes

---

### 🔗 Endpoints principales

* POST `/api/register`
* POST `/api/login`
* GET `/api/pizzas`
* POST `/api/orders/checkout`

---

## 🎨 Frontend (Vue 3)

### Requisitos

* Node.js >= 18
* npm >= 9

### Instalación

```bash
cd frontend
npm install
npm run dev
```

---

### ⚙️ Configuración

Crear archivo `.env` en frontend:

```env
VITE_API_URL=http://127.0.0.1:8000/api
```

---

## ✨ Funcionalidades

* Registro e inicio de sesión
* Visualización de menú de pizzas
* Carrito de compras
* Checkout de órdenes
* Envío de correo al completar pedido

---

## 🧠 Arquitectura

* Backend desacoplado (API REST)
* Frontend SPA con Vue 3
* Manejo de estado con Pinia
* Tests automatizados en backend

---

## 🛠️ Tecnologías

* Laravel
* Vue 3
* Vite
* Tailwind CSS
* Pinia
* Pest (testing)

---

## 📌 Notas

* Proyecto estructurado para escalabilidad
* Código organizado por dominio
* Buenas prácticas aplicadas (Requests, Services, Jobs)

---

## 📄 Licencia

MIT License
