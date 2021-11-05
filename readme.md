## Vanguard - Advanced PHP Login and User Management

Pasos para instalacion:

Requisitos del servidor

Con el fin de instalar la aplicación Vanguard, el servidor debe cumplir los requisitos siguientes:

PHP> = 5.5.9 (incluyendo PHP 7) o HHVM
OpenSSL PHP Extensión
DOP PHP Extensión
Mbstring PHP Extensión
Tokenizer PHP Extensión
GD PHP Extensión
Fileinfo PHP Extensión
Xdebug Nivel máximo de anidamiento (> = 500)

Instalación de Vanguard

Después de descargar los archivos, y subirlo a su servidor, lo primero que tiene que hacer es crear la base de datos donde se crearán las tablas del sistema. Digamos, se crea la base de datos llamada vanguardia.

Paso 1 - Pantalla de bienvenida

Después de crear la base de datos siguiente paso está accediendo a la URL de la aplicación desde un navegador. Una cosa que hay que saber es que laravel está diseñado para permitir el acceso HTTP a la aplicación desde su única carpeta pública. Esto significa que la aplicación Vanguard estará disponible en yourdomain.com/public. Es lo suficientemente bueno para la instalación y desarrollo, sin embargo, para la producción es probable que desee tener su solicitud disponible en yourdomain.com. En la siguiente sección voy a mostrar cómo se puede lograr eso.

Ok, ya que esta es la primera vez que se accede al sistema, el asistente de instalación, mostrará la pantalla de Bienvenida.

Paso 2 - Requisitos del sistema

Después de hacer clic en el botón "Siguiente", se le redirige a la segunda etapa durante el asistente de instalación, los requisitos del sistema.

Con el fin de instalar el script, el sistema debe tener instalado y activado todas las extensiones PHP que aparecen en ese segundo paso de la instalación.

Paso 3 - Permisos de directorio

Después de habilitar e instalar todas las extensiones PHP necesarias con éxito, el próximo paso es configurar los permisos apropiados para algunas carpetas del sistema.

Paso 4 - Información de Base de Datos

En el paso 4 se tiene que rellenar sus credenciales de base de datos y elegir un prefijo para las tablas de base de datos si lo desea. Por supuesto, puede dejar prefijo campo en blanco si no desea que sus tablas de la base de datos tengan un prefijo.

Paso 5 - Instalación

Después de pasar por todos esos pasos, que ahora está listo para instalar la aplicación. Si lo desea, puede cambiar nombre de la aplicación a otra cosa escribiendo el nombre de la aplicación aquí, antes de pulsar el botón Instalar.

Una vez que esté listo, sólo tiene que pulsar el botón del proceso y la instalación de instalación se iniciará inmediatamente. No debe tomar más de unos pocos segundos.

Paso 6 - Instalación completa

Una vez que la aplicación se completa con éxito, verá el último paso del asistente de instalación, según abajo

Se creará un usuario por defecto para usted con credenciales siguientes:

nombre de usuario: admin contraseña de administrador: admin123

Nota: para que el sistema ejecute por si solo los pasos de instalacion no debe estar creado el archivo .env y deben existir la carpeta vendor
