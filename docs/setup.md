# Instalacion del proyecto

El proyecto esta pensado para levantarse con unos comandos

## Prerequisitos

- Docker
- NPM
- [Ionic CLI](https://ionicframework.com/docs/intro/cli)


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

$ cd ./backend

# Crear un archivo .env en la raiz de la carpeta backend
# y copiar el contenido del .env.example en el .env
# y volver a la raiz del proyecto
$ cd ../

# Crear los contenedores del proyecto backend
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
$ exit

# Ingresar al contenedor de laravel
$ docker exec -it autominder_backend /bin/bash

# Correr las migraciones y agregar datos de prueba
$ php artisan migrate --seed

# Crear los secrets de los JWT
php artisan jwt:secret

# Crear los certificados de los JWT
php artisan jwt:generate-certs

# Generar documentacion de Swagger
php artisan l5-swagger:generate


# Salir del contenedor
$ exit
```
