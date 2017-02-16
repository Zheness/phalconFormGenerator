<?php

class MainTask extends \Phalcon\Cli\Task
{
    /**
     * entrypoint of the application.
     * Iterate on each table in the database and create the form fields in the output folder.
     * @param string $namespace
     * @param string $trait
     * @return int
     */
    public function mainAction($namespace = 'null', $trait = 'null')
    {
        if (!$this->checkDatabaseConnection()) {
            return 1;
        }
        if (!$this->checkOutputFolder()) {
            return 1;
        }
        echo "Congratulations! You are now flying with Phalcon CLI!";
        return 0;
    }

    /**
     * Checks the database connection.
     * @return bool
     */
    private function checkDatabaseConnection()
    {
        try {
            $this->db->listTables();
        } catch (PDOException $e) {
            echo "An error occured during connection to the database.", PHP_EOL;
            echo "Please check the config in `./app/config/config.php`.";
            return false;
        }
        return true;
    }

    /**
     * Checks if the output folder exists (or try to create it) and is writable.
     * @return bool
     */
    private function checkOutputFolder()
    {
        if (!file_exists(BASE_PATH . "/output")) {
            if (!mkdir(BASE_PATH . "/output", 0755)) {
                $base_path = BASE_PATH;
                echo "Folder `./output` cannot be created.", PHP_EOL;
                echo "Please check the permissions for `{$base_path}`.";
                return false;
            }
        }
        if (!is_writable(BASE_PATH . "/output")) {
            echo "Folder `./output` does not have the write permissions.", PHP_EOL;
            echo "Please check the permissions for `./output`.";
            return false;
        }
        return true;
    }
}
