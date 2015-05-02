***TDW User REST Api***
=================

> Desarrollo de una API REST con el framework Slim

# Introducción
------------------------------------------
Esta aplicación es una interfaz de programación [REST][rest] desarrollada como ejemplo de
utilización del framework [Slim][slim]. La aplicación proporciona las operaciones
habituales para la gestión de usuarios y grupos de usuarios. El proyecto se ha
desarrollado tomando como base los proyectos [there4/slim-unit-testing-example][unit_test_example]
y [there4/slim-test-helpers][slim_test_helpers].

Adicionalmente -para hacer más sencilla la gestión de los datos- se ha utilizado
el ORM [Doctrine][doctrine]. Doctrine 2 es un Object-Relational Mapper para 
PHP 5.4+ que proporciona persistencia transparente para objetos PHP. Utiliza
el patrón [Data Mapper][dataMapper] con el objetivo de obtener un desacoplamiento
completo entre la lógica de negocio y la persistencia de los datos en un SGBD.

La especificación de la API se ha elaborado empleando el editor [Swagger][swagger].
Además también se incluye la interfaz de usuario de esta fenomenal herramienta que
permite realizar pruebas interactivas de manera completa y elegante.

# Pruebas
------------------------------------------
La aplicación incorpora un conjunto de herramientas para la ejecución de pruebas 
unitarias y de integración. Empleando este conjunto de herramientas es posible
comprobar de manera automática el correcto funcionamiento de la aplicación completa
sin la necesidad de complejas herramientas software adicionales. Tan solo es 
necesario disponer del framework [PHPUnit][phpunit] instalado en el sistema.

## Ejemplo

Para testear la aplicación completa se ejecutará:

```
C:\xampp\htdocs\TDW_User2> phpunit
```

y si todo es correcto se obtendrá un resultado similar a:

```
PHPUnit 4.6.4 by Sebastian Bergmann and contributors.

Configuration read from C:\xampp\htdocs\TDW_User2\phpunit.xml

................................................................ 64 / 72 ( 88%)
........

Time: 2.12 seconds, Memory: 15.25Mb

OK (72 tests, 180 assertions)
```

Al ejecutar el comando `phpunit`, éste busca el fichero `phpunit.xml` en la raíz
del proyecto. Este fichero hace que PHPUnit incluya el fichero `test/bootstrap.php`,
que es el punto de entrada para la ejecución de las pruebas.

# Instalación
------------------------------------------
Clone el repositorio y después ejecute `composer install` para instalar las
dependencias del proyecto. Continúe con la configuración del proyecto
(fichero `config/config.php`) y la creación de la base de datos (ejecute los scripts
`app/scripts_aux/tdw_user.sql` y `app/scripts_aux/create_user_admin.php`). A continuación
ejecute `phpunit` (para obtener una lista detallada de los test realizados añada
la opción `--testdox`). La aplicación puede ejecutarse con un servidor web, o bien
empleando el servidor nativo del intérprete de PHP ejecutando desde el directorio
raíz del proyecto el comando `php -S localhost:8000 -t www/` y realizando una
petición con su navegador a [http://localhost:8000][lh].

# Estructura del proyecto
------------------------------------------
A continuación se describe el contenido y estructura del proyecto:
#### Directorio `app`:
* La aplicación (`app.php`)
* Clases auxiliares (`Auth`, `HTTP_Status` y `MySlim`)
* Entidades: clases PHP mapeadas a la base de datos a través del ORM
* Recursos de la aplicación (subdirectorio `resources`) con plantillas básicas de presentación
#### Directorio `build`:
* Ficheros de log y configuración para el servidor web Apache
#### Directorio `config`:
* `config.php` Fichero general de configuración de la aplicación (parámetros de 
Slim, base de datos, etc.)
* `bootstrap.php` y  `cli-config.php`: infraestructura del ORM
* Subdirectorio `yaml`: mapeo entre clases PHP y tablas de la base de datos en
formato [YAML][yaml]
#### Directorio `www`:
* `index.php` es el fichero de acceso a la aplicación. Inicializa la aplicación
Slim, incluye las rutas especificadas en `app/app.php` y ejecuta la aplicación.
* Subdirectorio `api`: cliente [Swagger][swagger] y especificación de la API.
#### Directorio `vendor`:
* Componentes desarrollados por terceros (Slim, Doctrine, etc.)
#### Directorio `test`:
* Conjunto de herramientas para la ejecución de test con PHPUnit.

# Agradecimientos
------------------------------------------
Deseo agradecer su trabajo al [Doctrine Team][doctrine], a [Josh Lockhart][jlockhart],
a [Sebastian Bergmann][sbergmann] y a los equipos de [Swagger][swagger] y
[There4 Development][there4].

[dataMapper]: http://martinfowler.com/eaaCatalog/dataMapper.html
[doctrine]: http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/
[jlockhart]: http://joshlockhart.com/
[lh]: http://localhost:8000
[phpunit]: http://phpunit.de/manual/current/en/index.html
[rest]: http://www.restapitutorial.com/
[sbergmann]: https://sebastian-bergmann.de/
[slim]: http://www.slimframework.com/
[slim_test_helpers]: https://github.com/there4/slim-test-helpers
[swagger]: http://swagger.io/
[there4]: http://there4development.com/
[unit_test_example]: https://github.com/there4/slim-unit-testing-example
[yaml]: http://yaml.org/