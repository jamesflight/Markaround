Markaround
==========
[![Build Status](https://travis-ci.org/jamesflight/Markaround.svg?branch=master)](https://travis-ci.org/jamesflight/Markaround)

Markaround is a PHP library for interacting with directories of Markdown files as if they were a read only database, and imitates the syntax of Eloquent ORM from [Laravel](http://laravel.com/).

The purpose for writing this library was to make it super easy to create blogs and document readers in PHP with no database, using directories of markdown files instead.

Bootstrapping
=============
Creating a new instance:
    <?php

    $config = [
        'default_path' => 'path/to/markdown/files'
    ];

    $markaround = Jamesflight\Markaround\Factory::create($config);

Laravel integration
-------------------
If you want to use in a Laravel project, add the following to the `providers` key of `config/app.php`:
    `'Jamesflight\Markaround\Laravel\MarkaroundServiceProvider'`

If you want to use as a Laravel facade, add the following to the `aliases` key of `config/app.php`:
    `'Markaround' => 'Jamesflight\Markaround\Laravel\Markaround'`

