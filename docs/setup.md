Instalacion del proyecto

El proyecto esta pensado para levantarse con unos comandos
Prerequisitos

    NPM
    Ionic CLI
    Docker

Iniciar el proyecto

``` bash
# Instalar dependencias de frontend

$ cd frontend/

$ npm install

# Iniciar el servidor del frontend

$ ionic serve

# Salir de la carpeta frontend

$ cd ../

# Instalar proyecto backend

$ docker-compose build --no-cache

# levantar contenedor de laravel y MySQL

$ docker-compose up -d

# Crear usuario MySQL

$ docker exec -it autominder_mysql /bin/bash

# Ingresar como usuario root

$ mysql -u root -p

# Deberia pedir contraseña

$ root

# Crear usuario

$ CREATE USER 'db_user'@'localhost' IDENTIFIED BY 'capstone2024.';
GRANT ALL PRIVILEGES ON autominder.\* TO 'db_user'@'localhost';
FLUSH PRIVILEGES;

# Salir del contenedor

$ exit

# Ingresar al contenedor de laravel

$ docker exec -it autominder_backend /bin/bash

# Correr las migraciones y agregar datos de prueba

$ php artisan migrate --seed

# Salir del contenedor

$ exit
```