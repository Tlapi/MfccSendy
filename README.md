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

```sh
php vendor/bin/doctrine orm:schema-tool:update --force
```

Set up db connection ``doctrineconnection.local.php.dist``

Insert ``data\mfsendy.sql``

Set up mandrill ``local.php.dist``
