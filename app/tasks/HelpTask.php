<?php

class HelpTask extends \Phalcon\Cli\Task
{
    public function mainAction()
    {
        $config = $this->getDI()->get('config');

        echo PHP_EOL, PHP_EOL;
        echo "PhalconFormGenerator ({$config['version']})";
        echo PHP_EOL, PHP_EOL;
        echo "Help:", PHP_EOL;
        echo "This script can generate form fields based on a database.", PHP_EOL;
        echo "Step 1:", PHP_EOL;
        echo "Edit database configuration in `./app/config/config.php`", PHP_EOL;
        echo "Step 2:", PHP_EOL;
        echo "Simply run `./run` to generate the files.";
        echo PHP_EOL, PHP_EOL;
        echo "Usage:", PHP_EOL;
        echo "./run [namespace] [trait]", PHP_EOL;
        echo PHP_EOL, PHP_EOL;
        echo "Arguments:", PHP_EOL;
        echo "help", PHP_EOL;
    }

}
