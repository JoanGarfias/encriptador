🧩 UNICRIPTER

Software de Encriptado y Desencriptado de Archivos de Texto

📖 Introducción

El presente proyecto titulado “UNICRIPTER” fue desarrollado con el propósito de crear un sistema web que permita encriptar y desencriptar archivos de texto (.txt) de forma segura, sencilla y gratuita.

El software surge como una práctica académica para comprender y aplicar los principios de seguridad informática, enfocándose en el proceso de cifrado de datos y su posterior recuperación mediante una llave única.

UNICRIPTER busca brindar una interfaz intuitiva y funcional que permita a los usuarios proteger la información contenida en sus archivos de texto, evitando el acceso no autorizado mediante técnicas básicas de encriptación implementadas en Laravel (PHP) y Vue.js.

⚙️ Objetivo del Proyecto

Desarrollar una aplicación web funcional que permita encriptar archivos de texto mediante un algoritmo propio, generar una llave de seguridad (.key) y, posteriormente, permitir su desencriptación usando dicha llave.

Además, se pretende reforzar conocimientos en:

Programación orientada a servicios (API).

Manejo de archivos y almacenamiento seguro.

Interacción entre backend (Laravel) y frontend (Vue + Inertia).

Buenas prácticas de seguridad y validación de datos.

🧠 Funcionamiento del Algoritmo de Encriptación

El proceso de encriptación en UNICRIPTER está implementado dentro del controlador Encriptar.php y utiliza la clase EncryptionService.

El flujo general del algoritmo es el siguiente:

Carga del archivo:
El usuario selecciona o arrastra un archivo .txt a la interfaz web.
El archivo se envía al servidor mediante una solicitud POST usando Fetch API.

Generación de llave aleatoria:
El sistema crea una llave de 20 caracteres utilizando una combinación de números del 0 al 9 y letras mayúsculas del alfabeto.
Esta llave será única para cada archivo y se guarda como un archivo .key.

Proceso de encriptación:
El texto del archivo original se pasa al método encriptar(), el cual modifica los caracteres del texto con base en la llave generada.
El resultado es un archivo cifrado ilegible para el usuario, que solo puede ser restaurado si se usa la misma llave.

Generación de archivos de salida:
Una vez completado el proceso, el sistema genera dos archivos:

encriptado_xxxxx.txt → Contiene el texto cifrado.

key_xxxxx.key → Contiene la llave de encriptación.

Desencriptación:
Para recuperar el archivo original, el usuario debe subir ambos archivos al sistema.
El método desencriptar() toma el texto cifrado y la llave para revertir el proceso, devolviendo el texto plano original en un nuevo archivo .txt.

Almacenamiento y descarga:
Los archivos generados se guardan en la carpeta storage/app/public/downloads/, y pueden ser descargados desde la interfaz mediante los enlaces dinámicos generados en el frontend.

🖥️ Estructura de las Vistas (Frontend)

La interfaz gráfica fue desarrollada con Vue 3 + Inertia + TailwindCSS, y está dividida en dos secciones principales dentro del componente Welcome.vue:

Pestaña “Encriptar”
Permite seleccionar o arrastrar un archivo .txt, muestra una barra de progreso y un modal de confirmación cuando el proceso termina.
También habilita la descarga del archivo cifrado y su llave correspondiente.

Pestaña “Desencriptar”
Solicita el archivo .txt cifrado y su .key.
Tras procesarlos, permite descargar el archivo original desencriptado.

La aplicación también integra temas claro/oscuro, un menú responsivo y autenticación de usuario mediante Laravel Breeze, lo que permite guardar el historial de archivos encriptados por usuario.

🧩 Tecnologías Utilizadas
Componente	Tecnología
Lenguaje principal	PHP 8.x
Framework Backend	Laravel 11
Framework Frontend	Vue.js 3 (con Inertia.js)
Estilos	Tailwind CSS
Almacenamiento de archivos	Laravel Storage (disco público)
Base de datos	MySQL
Control de versiones	Git / GitHub
🚀 Instalación y Ejecución

Clonar el repositorio

git clone https://github.com/usuario/unicripter.git
cd unicripter


Instalar dependencias de Laravel

composer install
cp .env.example .env
php artisan key:generate


Configurar la base de datos
Editar el archivo .env con tus credenciales de MySQL.

Instalar dependencias del frontend

npm install
npm run dev


Ejecutar el servidor

php artisan serve


Abrir en el navegador

http://localhost:8000

🧾 Conclusión

El desarrollo de UNICRIPTER permitió comprender a fondo los procesos de encriptación, desencriptación y manejo de archivos dentro de un entorno web moderno.

El proyecto integra múltiples tecnologías de desarrollo web, reforzando los conocimientos sobre comunicación entre cliente y servidor, seguridad de datos, y persistencia de información mediante bases de datos y almacenamiento local.

Asimismo, la implementación de un diseño modular y una interfaz responsiva hace que el sistema sea escalable y adaptable a proyectos más complejos de seguridad y cifrado en el futuro.