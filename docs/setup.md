# Instalacion del proyecto

El proyecto esta pensado para levantarse con unos comandos

## Prerequisitos

- NPM
- Ionic CLI
- Composer
- PHP
- MySQL

## Iniciar el proyecto

```bash
# Instalar dependencias de frontend
$ cd frontend/

$ npm install

# Iniciar el servidor del frontend
$ ionic serve

# Salir de la carpeta frontend
$ cd ../

# Instalar dependecias del servidor
$ cd backend/

$ composer install

# Correr las migraciones y agregar los datos de prueba

$ php artisan migrate --seed

# Iniciar servidor del backend
$ php artisan serve

```
