# phalconFormGenerator

VERSION 2.0

phalconFormGenerator is a simple script used to generate forms according to the columns of a table.

Note: this version 2.0 no longer require a web server to run. Simply install the packages and launch the script!

## Installation and usage

1. Download this repo
2. Run `composer install` command to install packages
3. Configure database access in `app/config/config.php`
4. Start the generation with `./run`

You can run `./run help` to read the help for the command.

The files will be generated in the `output` folder.

## Example:

You have a table **users** with inside a column **firstname** (of type *VARCHAR* and size *75*);

After running the script, this code will be generated:

```php
class UsersForm extends \Phalcon\Forms\Form
{
    public function setFields()
    {
        $this->add($this->firstnameField());
    }
    
    private function firstnameField()
    {
        $element = new \Phalcon\Forms\Element\Text("firstname");
        $element->setLabel("firstname");
        $element->addValidator(new \Phalcon\Validation\Validator\StringLength([
            "max" => 75
        ]));
        return $element;
    }
}
```

For another example, the folder `example` contains the structure of a database and the files generated.

## What's next ?

Some type of fields are not generated, selects are empty, relationships are not implemented, etc.

If you want to contribute, feel free to!

## About

This project was tested with this configuration:

* PHP : 7.0.12
* MySQL : 5.7.16
* Phalcon : 3.0.3