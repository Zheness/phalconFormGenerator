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
            $this->db->connect();
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

    /**
     * Return the Phalcon type (Numeric, Date, Text, ...) according to column type.
     * @param string $type
     * @return string
     */
    private function getPhalconBaseType($type)
    {
        $types = [
            \Phalcon\Db\Column::TYPE_INTEGER => "Numeric",
            \Phalcon\Db\Column::TYPE_DATE => "Date",
            \Phalcon\Db\Column::TYPE_VARCHAR => "Text",
            \Phalcon\Db\Column::TYPE_DECIMAL => "Numeric",
            \Phalcon\Db\Column::TYPE_DATETIME => "Text",
            \Phalcon\Db\Column::TYPE_CHAR => "Text",
            \Phalcon\Db\Column::TYPE_TEXT => "Textarea",
            \Phalcon\Db\Column::TYPE_FLOAT => "Numeric",
            \Phalcon\Db\Column::TYPE_BOOLEAN => "Numeric",
            \Phalcon\Db\Column::TYPE_DOUBLE => "Numeric",
            \Phalcon\Db\Column::TYPE_TINYBLOB => "Textarea",
            \Phalcon\Db\Column::TYPE_BLOB => "Textarea",
            \Phalcon\Db\Column::TYPE_MEDIUMBLOB => "Textarea",
            \Phalcon\Db\Column::TYPE_LONGBLOB => "Textarea",
            \Phalcon\Db\Column::TYPE_BIGINTEGER => "Numeric",
            \Phalcon\Db\Column::TYPE_JSON => "Textarea",
            \Phalcon\Db\Column::TYPE_JSONB => "Textarea",
            \Phalcon\Db\Column::TYPE_TIMESTAMP => "Numeric"
        ];

        return isset($types[$type]) ? $types[$type] : "Text";
    }

    /**
     * Return a better Phalcon type (Check, Select, Password, ...) according to the column.
     * @param \Phalcon\Db\Column $column
     * @return string
     */
    private function getBestPhalconType($column)
    {
        $size = $column->getSize();
        $columntype = $this->getPhalconBaseType($column->getType());

        if ($columntype == "Numeric") {
            if ($size == 1) {
                $columntype = "Check";
            } else if ($size == 11) {
                // Could be also a Radio but Select is arbitrary choose here
                $columntype = "Select";
            }
        } else if ($columntype == "Text") {
            if ($size > 255) {
                $columntype = "Textarea";
            }
            if (preg_match("#(pass|pwd)#i", $column->getName())) {
                $columntype = "Password";
            }
        }

        return $columntype;
    }
}
