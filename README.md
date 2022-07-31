Comandos a ejecutar:
```shell
# update composer
$ composer update

# Crear una llave laravel
$ php artisan key:generate

# Correr migraciones
$ php artisan migrate

```

correr los inserts de paises.sql y ciudades.sql en este orden

```shell
# Correr el seeder de la tabla User
$ php artisan db:seed --class=UserSeeder

# comando artisan para enviar la cola de emails
$ php artisan mails:send

```
