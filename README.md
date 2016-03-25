Тестовое задание
================

Внимание!
Данный скрипт не предназначен для работы в продакшене.


Installation
------------

```bash
$ git clone git@github.com:Cimus/tz6.git tz
$ cd tz
$ composer install
```

Usage
-----
В файле app.php сменить настройки подключения к БД.

Выполнить инструкции из файла dump.sql

```php
php app.php //Выводит список комманд

php app.php load:banners   //Загружает объявления
php app.php load:campaigns //  Загружает кампании
php app.php stat           // Выводит статистику слов в ключевых фразах

```