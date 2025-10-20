# Unicripter - Deployment Guide

## Despliegue Automático

Para reconstruir y desplegar la aplicación con los assets actualizados:

```bash
./deploy.sh
```

Este script automáticamente:
1. Construye la imagen Docker con los assets compilados
2. Extrae los assets (CSS/JS) al directorio `public/` del host
3. Reinicia el contenedor

## Despliegue Manual

Si prefieres hacerlo paso a paso:

```bash
# 1. Build de la imagen
docker-compose build

# 2. Copiar assets del contenedor al host (para que Nginx pueda servirlos)
docker run --rm -v "$(pwd)/public:/target" unicripter:latest sh -c "cp -r /var/www/html/public/* /target/"

# 3. Reiniciar contenedor
docker-compose down && docker-compose up -d
```

## ¿Por qué copiar assets?

El contenedor PHP-FPM compila los assets durante el build, pero Nginx (que corre en otro contenedor)
necesita acceso directo a los archivos estáticos (CSS/JS) para servirlos eficientemente.

La carpeta `public/` del host está montada en el contenedor de Nginx para servir estos archivos.
