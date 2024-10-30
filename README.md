# Aires de cambios

## Requisitos

Antes de correr la aplicación, asegúrate de tener instalados los siguientes componentes en tu sistema:

    - PHP: Versión 8.0 o superior
    - Composer: Para gestionar las dependencias de PHP.
    - Node.js y npm: Para gestionar dependencias de JavaScript. Se recomienda Node.js 14.x o superior.
    - PostgreSQL (u otra base de datos soportada): Asegúrate de tener una base de datos creada para el proyecto.

------------------------------------------------------------------

## Instrucciones para ejecutar el proyecto

### Utilizar una consola a gusto con la dirección del proyecto y escribir los siguientes comandos:

1. Instalar dependencias de PHP usando Composer:

`composer install`

2. Instalar dependencias de Node.js

`npm install`

3. Configurar el archivo .env

Copia el archivo .env.example y renómbralo a .env:

`cp .env.example .env`

Luego, abre el archivo .env y asegúrate de configurar las siguientes variables:

    BASE DE DATOS: Ajusta las variables DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD según tu configuración local.
    APP_KEY: Genera la clave de la aplicación con el siguiente comando:

`php artisan key:generate`

    WEATHER_API_KEY: Insertar key de Weather API para poder obtener los datos climáticos.

4. Ejecutar migraciones

Si estás iniciando la aplicación por primera vez o necesitas actualizar las tablas de la base de datos, corre las migraciones:

`php artisan migrate`

5. Compilar los archivos frontend

Para compilar los archivos de Vue.js y otros recursos frontend, utiliza:

`npm run dev`

6. Iniciar el servidor local

Finalmente, para iniciar la aplicación en modo desarrollo, usa:

`php artisan serve`

El servidor por defecto estará disponible en http://localhost:8000.


### Comandos adicionales

Limpiar caché de configuración:

`php artisan config:clear`

Siguiendo estos pasos deberías poder ejecutar correctamente la aplicación Aires de cambios