Markaround
==========
[![Build Status](https://travis-ci.org/jamesflight/Markaround.svg?branch=master)](https://travis-ci.org/jamesflight/Markaround)

Markaround is a PHP library for interacting with directories of Markdown files as if they were a read only database, and imitates the syntax of Eloquent ORM from [Laravel](http://laravel.com/).

The purpose of this is to make it super easy to create blogs and document readers in PHP with no database, using directories of markdown files instead.

Bootstrapping
=============
Creating a new instance:
    <?php

    $config = [
        'default_path' => 'path/to/markdown/files'
    ];

    $markaround = Jamesflight\Markaround\Factory::create($config);


Usage
=====
Saving Markdown files:
----------------------

Markdown files should be saved with one of the following naming conventions:

File retrievable by slug:

`file-slug.md`

File retrievable by id:

`01_.md`

File retrievable by date:

`2001-01-01.md`

These can be combined in any combination, in the order id, date, slug:

`01_2001-01-01-file-slug.md`

`01_file-slug.md`

`2001-01-01-file-slug.md`



Retrieving files:
---------------
**Retrieving all files:**

    $posts = $markaround->all();

**Retrieving a file by id:**

    $posts = $markaround->find(1);
    $posts = $markaround->findOrFail(1); // Throws a MarkdownFileNotFoundException if not found

**Retrieving files by date**

Note: any valid PHP datetime format can be used.

    $posts = $markaround->where('date', '1st January 2015')->get();

**Retrieving files by slug**

    $posts = $markaround->where('slug', 'file-slug')->get();

**Retrieving the first entry from the result collection**

    $post = $markaround->where('date', '1st January 2015')->first();
    $post = $markaround->where('date', '1st January 2015')->firstOrFail(); // Throws a MarkdownFileNotFoundException if not found

**Retrieving where one condition or another is true**

    $post = $markaround->where('id', 1)
                ->orWhere('id', 2)
                ->get();

Comparison operators
---------------------------
You can provide a comparison operator as the second argument in a where (or orWhere) query:

    $posts = $markaround->where('id', '=', 1)->first();

Available operators are:

Equal to: `=`

Not equal to: `!=`

Less than: `<`

Greater than: `>`

Greater than or equal to: `>=`

Less than or equal to: `<=`

Here are some examples of their use:

    $posts = $markaround->where('id', '!=', 1)->get();
    $posts = $markaround->where('date', '>=', '1st January 2015')->get();


Custom Fields
-------------
At the top of each markdown file, you have the option of adding extra fields using YAML that you can also query by:


    ---
    foofield: foo
    barfield: bar
    ---
    Markdown goes here

Supplying these custom fields allows for the following:

    $posts = $markdown->where('foofield', '=', 'foo')->first();