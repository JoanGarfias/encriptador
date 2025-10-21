# Guía de Despliegue y Ejecución Local

Este documento proporciona las instrucciones necesarias para configurar y ejecutar la aplicación en un entorno de desarrollo local.

## Tecnologías Utilizadas

La aplicación está construida con las siguientes tecnologías:

-   **Backend**:
    -   PHP 8.3+
    -   Laravel 11
-   **Frontend**:
    -   Vue.js 3 Composition API
    -   TypeScript
    -   Inertia.js
    -   Tailwind CSS
    -   shadcn-vue
-   **Base de datos**:
    -   Compatible con MySQL, PostgreSQL, SQLite.
-   **Servidor de desarrollo**:
    -   Servidor de desarrollo de Vite.
-   **Gestores de paquetes**:
    -   Composer para dependencias de PHP.
    -   npm para dependencias de JavaScript.

## Requisitos

Antes de comenzar, asegúrate de tener instalado lo siguiente:

-   PHP >= 8.3
-   Composer
-   Node.js >= 18.x
-   npm
-   Un servidor de base de datos (ej. MySQL, PostgreSQL, SQLite).

## Pasos para la Instalación

1.  **Clonar el repositorio**:
    ```bash
    git clone https://github.com/JoanGarfias/encriptador.git
    cd encriptador
    ```

2.  **Instalar dependencias de PHP**:
    ```bash
    composer install
    ```

3.  **Instalar dependencias de JavaScript**:
    ```bash
    npm install
    ```

4.  **Configurar el archivo de entorno**:
    Copia el archivo de ejemplo `.env.example` y renómbralo a `.env`.
    ```bash
    cp .env.example .env
    ```
    Abre el archivo `.env` y configura las credenciales de tu base de datos (DB_DATABASE, DB_USERNAME, DB_PASSWORD, etc.).

5.  **Generar la clave de la aplicación**:
    ```bash
    php artisan key:generate
    ```

6.  **Ejecutar las migraciones de la base de datos**:
    Esto creará las tablas necesarias en tu base de datos.
    ```bash
    php artisan migrate
    ```

7.  **Compilar los assets del frontend**:
    Este comando compilará los archivos de Vue.js y Tailwind CSS.
    ```bash
    npm run dev
    ```

8.  **Iniciar el servidor de desarrollo**:
    En una terminal separada, ejecuta el siguiente comando para iniciar el servidor de Laravel.
    ```bash
    php artisan serve
    ```

Una vez completados estos pasos, la aplicación estará disponible en `http://127.0.0.1:8000`.
