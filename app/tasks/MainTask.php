<?php

class MainTask extends \Phalcon\Cli\Task
{
    private $outputFolder = BASE_PATH . "/output";

    /**
     * entrypoint of the application.
     * Iterate on each table in the database and create the form fields in the output folder.
     * @param array $arguments
     * @return int
     */
    public function mainAction($arguments = [])
    {
        if (!$this->checkDatabaseConnection()) {
            return 1;
        }
        if (!$this->checkOutputFolder()) {
            return 1;
        }

        $namespaceName = (isset($arguments[0]) && $arguments[0] != 'none') ? $arguments[0] : null;
        $traitName = (isset($arguments[1]) && $arguments[1] != 'none') ? $arguments[1] : null;
        $extendsName = '\Phalcon\Forms\Form';
        $extendsName = isset($arguments[2]) ? ($arguments[2] == 'null' ? $extendsName : ($arguments[2] == 'none' ? null : $arguments[2])) : $extendsName;

        echo "Generation started.", PHP_EOL;

        $tables = $this->db->listTables();
        foreach ($tables as $table) {
            echo "Processing table `{$table}`... ";

            $className = \Phalcon\Text::camelize($table);

            $class = new \Nette\PhpGenerator\ClassType($className . "Form");
            if ($extendsName != null) {
                $class->setExtends($extendsName);
            }
            if ($traitName != null) {
                $class->addTrait($traitName);
            }

            $columnsList = $this->db->describeColumns($table);
            foreach ($columnsList as $column) {
                if ($column->isPrimary()) {
                    continue;
                }
                $columnInfo = [
                    "name" => lcfirst(\Phalcon\Text::camelize($column->getName())),
                    "type" => $this->getBestPhalconType($column),
                    "size" => $column->getSize()
                ];
                $method = $class->addMethod($columnInfo['name'] . "Field");
                $method->setVisibility('private');
                $method->addBody('$element = new \Phalcon\Forms\Element\\' . $columnInfo['type'] . '("' . $columnInfo['name'] . '");');
                $method->addBody('$element->setLabel("' . $columnInfo['name'] . '");');
                if ($columnInfo['type'] == 'Select') {
                    $method->addBody('$element->setOptions([]);');
                }

                if ($columnInfo['type'] == 'Text' && $columnInfo['size']) {
                    $method->addBody('$element->addValidator(new \Phalcon\Validation\Validator\StringLength([');
                    $method->addBody('    "max" => ' . $columnInfo['size']);
                    $method->addBody(']));');
                }
                $method->addBody('return $element;');
            }

            $content = "<?php" . PHP_EOL . PHP_EOL;
            if ($namespaceName != null) {
                $content .= "namespace " . $namespaceName . ";" . PHP_EOL . PHP_EOL;
            }
            $content .= (string)$class;

            if (file_put_contents($this->outputFolder . "/" . $className . "Form.php", $content) === false) {
                echo "Impossible to write the file in the output folder.", PHP_EOL;
                echo "Please check the permissions for `{$this->outputFolder}`.", PHP_EOL;
                echo "Script aborted.";
                return 1;
            }

            echo "done", PHP_EOL;
        }

        echo "Generation finished.";

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
        if (!file_exists($this->outputFolder)) {
            if (!mkdir($this->outputFolder, 0755)) {
                $base_path = BASE_PATH;
                echo "Folder `{$this->outputFolder}` cannot be created.", PHP_EOL;
                echo "Please check the permissions for `{$base_path}`.";
                return false;
            }
        }
        if (!is_writable($this->outputFolder)) {
            echo "Folder `{$this->outputFolder}` does not have the write permissions.", PHP_EOL;
            echo "Please check the permissions for `{$this->outputFolder}`.";
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
     * @param \Phalcon\Db\ColumnInterface $column
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
