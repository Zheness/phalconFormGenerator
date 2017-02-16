<?php

class MainTask extends \Phalcon\Cli\Task
{
    public function mainAction($namespace = 'null', $trait = 'null')
    {
        echo "Congratulations! You are now flying with Phalcon CLI!";
    }

}
