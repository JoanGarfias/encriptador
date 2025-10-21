🧩 UNICRIPTER

Software de Encriptado y Desencriptado de Archivos de Texto

-------------------------------------------------------------


> [!WARNING] Esta es una práctica académica y no debe ser utilizado para fines de seguridad en entornos críticos.
> [!IMPORTANT] Este archivo es la explicación técnica del proyecto UNICRIPTER. Si deseas obtener una guía para correr el proyecto en tu máquina local, visita [Guía de Despliegue](DEPLOY.md):

📖 Introducción

El presente proyecto titulado “UNICRIPTER” fue desarrollado con el propósito de implementar un sistema web que permita encriptar y desencriptar archivos de texto (.txt) de forma segura, sencilla y gratuita.

Este software fue diseñado como una práctica académica en el área de Seguridad Informática dentro de la carrera de Ingeniería en Computación, con el objetivo de comprender de manera práctica los fundamentos de la criptografía y la protección de datos.

UNICRIPTER busca ofrecer una experiencia de usuario intuitiva, moderna y funcional, al mismo tiempo que aplica técnicas de desarrollo web profesional mediante la integración de Laravel y Vue.js, tecnologías ampliamente utilizadas en la industria del software.

-------------------------------------------------------------

⚙️ Objetivo del Proyecto

Desarrollar una aplicación web que permita a los usuarios encriptar archivos de texto a través de un algoritmo propio y generar una llave de seguridad (.key) para su posterior desencriptación.

Además de cumplir con los objetivos técnicos, el proyecto busca fomentar el trabajo en equipo, la organización del código y la comprensión de temas clave como:

+ Programación web orientada a servicios (API REST).

+ Seguridad y cifrado de información.

+ Integración entre frontend y backend.

+ Buenas prácticas de documentación y control de versiones.
  
-------------------------------------------------------------

🧠 Funcionamiento del Algoritmo de Encriptación

El proceso de cifrado en UNICRIPTER se desarrolla principalmente en el controlador Encriptar.php y la clase auxiliar EncryptionService.

🔹 Etapas del proceso:

Carga del archivo (.txt):
El usuario selecciona o arrastra un archivo de texto a la interfaz.
El archivo es enviado al servidor mediante una solicitud POST con el método fetch().

Generación de la llave de seguridad:
El sistema genera una clave aleatoria de 20 caracteres a partir de números y letras mayúsculas (A–Z, 0–9).
Esta clave servirá como base para transformar el contenido del archivo durante el cifrado.

Aplicación del algoritmo de encriptación:
El contenido del archivo es transformado utilizando la clave generada.
El algoritmo consiste en recorrer el texto y modificar el valor de cada carácter con base en la posición y composición de la llave.
El resultado es un texto ilegible sin la llave original.

Generación de los archivos de salida:
Tras el proceso, se generan dos archivos:

encriptado_XXXX.txt → Contiene el texto cifrado.

key_XXXX.key → Contiene la clave necesaria para revertir el proceso.

Desencriptación:
Para recuperar el contenido original, el usuario debe subir el archivo cifrado y su llave.
El sistema aplica el algoritmo inverso, utilizando la misma clave para reconstruir el texto original.

Descarga y almacenamiento:
Los archivos se almacenan en el directorio storage/app/public/downloads/ y se ponen a disposición del usuario mediante enlaces de descarga generados dinámicamente.

-------------------------------------------------------------

🖥️ Estructura de las Vistas (Frontend)

El frontend fue construido con Vue 3, utilizando el framework Inertia.js para la comunicación con el backend en Laravel y TailwindCSS para los estilos visuales.

🔹 Componentes principales:

Welcome.vue:
Contiene la interfaz principal con dos pestañas:

Encriptar: Permite subir el archivo .txt, muestra una barra de progreso y genera enlaces de descarga de los archivos resultantes.

Desencriptar: Permite subir el archivo cifrado y su llave .key para recuperar el texto original.

Diseño responsivo:
El sistema cuenta con soporte para tema claro/oscuro, adaptándose automáticamente al modo del navegador o elección del usuario.

Modales y progreso:
Se implementan modales de confirmación y una barra de progreso animada que simula el procesamiento de los archivos en tiempo real.

Autenticación:
Gracias a Laravel Breeze, los usuarios pueden iniciar sesión, registrarse y consultar el historial de archivos encriptados asociados a su cuenta.

-------------------------------------------------------------

🧩 Tecnologías Utilizadas

<img width="1001" height="536" alt="image" src="https://github.com/user-attachments/assets/8a6ecb54-51cd-4a90-803a-2b0af6a9311b" />

📊 Resultados Obtenidos

El sistema UNICRIPTER logró cumplir con los objetivos planteados, permitiendo:

Encriptar y desencriptar archivos .txt de manera exitosa.

Generar llaves únicas por cada archivo procesado.

Almacenar y descargar los resultados de forma segura.

Implementar una interfaz moderna, accesible y responsiva.

Integrar autenticación y manejo de historial por usuario.

Esto demuestra la correcta integración entre los distintos componentes del ecosistema Laravel + Vue, así como la comprensión del flujo de datos en un entorno de cifrado real.

-------------------------------------------------------------

🧾 Conclusión

El desarrollo de UNICRIPTER permitió aplicar de manera práctica los conocimientos teóricos sobre encriptación de información, validación de datos y comunicación cliente-servidor, además de fortalecer habilidades en programación web moderna.

El proyecto representa una base sólida para futuras mejoras, como la implementación de algoritmos de cifrado más robustos (AES, RSA) o el almacenamiento seguro de llaves en servidores externos.

Asimismo, el trabajo en equipo y el uso de herramientas de control de versiones facilitaron la colaboración y documentación del proyecto, cumpliendo con los estándares de desarrollo profesional.

-------------------------------------------------------------

👥 Créditos del Equipo de Desarrollo

Proyecto desarrollado por estudiantes de Ingeniería en Computación
Universidad del Istmo – Campus Tehuantepec

Acevedo Terán Gerardo.
Alvarado Garfias Joan Pablo. 
Becerril Nolasco Juan Emmanuel.
Cordero Luna Karla Guadalupe.
Jiménez Charis Isacar.
Jiménez Pacheco Ángel Daniel.
Osorio Ramos Jeremy.
