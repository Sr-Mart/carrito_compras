# Carrito de Compras

Este proyecto es una aplicación web de carrito de compras desarrollada en PHP utilizando el patrón MVC (Modelo-Vista-Controlador). La aplicación permite a los usuarios iniciar sesión, crear usuarios, agregar productos a su carrito de compras y pagar.

## Estructura del Proyecto

La estructura del proyecto es la siguiente:

composer.json
composer.lock
db.txt
index.php
assets/
    ├── css/
    ├── img/
    ├── vendor/
config/
controllers/
js/
models/
tests/
views/


### Directorios Principales

- `assets/`: Contiene archivos estáticos como CSS, imágenes y librerías JavaScript.
- `config/`: Contiene archivos de configuración como `config.php`.
- `controllers/`: Contiene los controladores de la aplicación.
- `js/`: Contiene archivos JavaScript personalizados.
- `models/`: Contiene los modelos de la aplicación.
- `tests/`: Contiene pruebas para los modelos.
- `views/`: Contiene las vistas de la aplicación.

## Instalación

Para instalar y configurar la aplicación, sigue estos pasos:

1. Clona el repositorio en tu máquina local:

   git clone https://github.com/tu-usuario/carrito-de-compras.git
   cd carrito-de-compras

2. Instala las dependencias de PHP usando Composer:

composer install

3. Instala las dependencias de npm:

cd assets

npm install

composer install

3. Configura la base de datos en el archivo config/config.php:

return [
    'host' => 'localhost',
    'dbname' => 'nombre_de_tu_base_de_datos',
    'username' => 'tu_usuario',
    'password' => 'tu_contraseña',
];

4. Crea la base de datos y las tablas necesarias. Puedes usar el archivo db.txt para obtener el esquema de la base de datos.


5. Inicia el servidor web integrado de PHP:

6. Abre tu navegador web y navega a http://localhost:8000.

7. Abre tu navegador web y navega a http://localhost:8000.

