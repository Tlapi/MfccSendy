MFCC mailer
=======================

Installation
-------

```sh
php composer.phar self-update
```

```sh
php composer.phar install
```

Set up db connection ``config/autoload/doctrineconnection.local.php.dist``

Insert ``data/mfsendy.sql``

```sh
php vendor/bin/doctrine orm:schema-tool:update --force
```

Set up mandrill ``config/autoload/local.php.dist``
