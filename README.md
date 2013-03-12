OpenEquestrianClubManagement
============================

Application de gestion de centre équestre

Installation
------------

- git clone https://github.com/leblanc-simon/OpenEquestrianClubManagement.git
- cd OpenEquestrianClubManagement
- cp config/config.php.example config/config.php
- créer un fichier config/runtime-conf.xml pour l'accès à la base de données (voir [la documentation](http://propelorm.org/reference/runtime-configuration))
- cp config/runtime-conf.xml buildtime-conf.xml
- composer install
- vendor/propel/propel1/generator/bin/propel-gen om
- vendor/propel/propel1/generator/bin/propel-gen sql
- vendor/propel/propel1/generator/bin/propel-gen convert-conf
- vendor/propel/propel1/generator/bin/propel-gen insert-sql
- faire pointer le DocumentRoot vers OpenEquestrianClubManagement/web

Licence
-------

[MIT](http://opensource.org/licenses/MIT)
