# phalconFormGenerator

This project is a simple tool created to automatically generate forms for Phalcon Framework.

Here is the scenario : you imported a database, and you must create all the forms attached to your tables.

Simply download this repository, configure the database connection, and run the website ! All the forms will be generated.  
You can now copy the files in your real project.

The forms are not perfect, and not all type of field are supported, but its help to generate a lot of fields in one shot.

**[TL;DR]**

Just open `website/phalconformgenerator.sql` and look the table users.sql

Now look the form generated according to this table in file `website/app/forms/UsersForm.php`

## Configuration

Download the repository, create or set up the virtual host to run the website.

Edit `website/app/config/config.php` and change the database connection.

Start the webserver, and go to the URL `/index/reset` to generate the files.  
The files are generated under `website/app/forms/` folder.

That's it ! You can copy the files into your project.

## Test the render of a form

This part is not mandatory for generation.

The repository use default phalcon-devtool generation, using volt syntax and Bootstrap Framework for CSS.

For testing purpose, another project ([https://github.com/Zheness/phalconCssForm](https://github.com/Zheness/phalconCssForm)) is used here to render a form.

Open the file `website/app/forms/UsersForm.php` and add `extends phalconCSSFormBootstrap` after the class name.

Open the URL `/index/tmp`.

## What's next ?

Some type of fields are not generated, selects are empty, relationships are not implemented, etc.

If you want to contribute, feel free to!

## About

This project was tested with this configuration:

* Apache : 2.4.9
* PHP : 5.6.13
* MySQL : 5.6.17
* Phalcon : 2.0.7