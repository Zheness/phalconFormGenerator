<?php

class IndexController extends ControllerBase
{
    const NL = "\n";
    const TAB = "\t";

    public function indexAction()
    {
        $connection = new \Phalcon\Db\Adapter\Pdo\Mysql($this->config->database->toArray());
        $tables = $connection->listTables();
        foreach ($tables as $table) {

            $tablename = \Phalcon\Text::camelize($table);
        	$fd = fopen("{$this->config->application->formsDir}/{$tablename}.php", "w");
        	
        	fwrite($fd, "<?php" . self::NL . self::NL);

            // Begin class
            fwrite($fd, "class {$tablename}Form extends \\Phalcon\\Forms\\Form {" . self::NL);


            $columns = $connection->describeColumns($table);
            foreach ($columns as $column) {
                if ($column instanceof \Phalcon\Db\Column) {

                    // Escape if column is primary
                    if ($column->isPrimary())
                        continue;

                    // Begin method
                    $columnname = \Phalcon\Text::camelize($column->getName());
                    fwrite($fd, self::TAB . "private function _{$columnname}() {" . self::NL);

                    // Write element
                    $columntype_base = $this->_getBaseType($column->getType());
                    $columntype = $this->_getType($columntype_base, $column);
                    fwrite($fd, self::TAB . self::TAB . "\$element = new \\Phalcon\\Forms\\Element\\{$columntype}(\"{$columnname}\");" . self::NL);
                    fwrite($fd, self::TAB . self::TAB . "\$element->setLabel(\"{$columnname}\");" . self::NL);

                    // Add validator on text fields
                    if ($columntype == "Text" && $column->getSize() > 0) {
                        fwrite($fd, self::TAB . self::TAB . "\$element->addValidator(new \\Phalcon\\Validation\\Validator\\StringLength([" . self::NL);
                        fwrite($fd, self::TAB . self::TAB . self::TAB . "\"max\" => {$column->getSize()}" . self::NL);
                        fwrite($fd, self::TAB . self::TAB . "]));" . self::NL);
                    }

                    // End method
                    fwrite($fd, self::TAB . self::TAB . "return \$element;" . self::NL);
                    fwrite($fd, self::TAB . "}" . self::NL);
                }
            }

            // Final method : construction of the form
            fwrite($fd, self::TAB . "public function setFields() {" . self::NL);
            foreach ($columns as $column) {
                if ($column instanceof \Phalcon\Db\Column) {
                    if ($column->isPrimary())
                        continue;
                    $columnname = \Phalcon\Text::camelize($column->getName());
                    fwrite($fd, self::TAB . self::TAB . "\$this->add(\$this->_{$columnname}());" . self::NL);
                }
            }
            fwrite($fd, self::TAB . "}" . self::NL);

            // End class
            fwrite($fd, "}" . self::NL . self::NL);

            fclose($fd);
        }
        $this->view->disable();

        echo "done!";

        return FALSE;
    }

    /**
     * Return a better Phalcon type (Check, Select, Password, ...) according to previous Phalcon type
     * and size of the column
     * @param string $type
     * @param \Phalcon\Db\Column $column
     * @return string
     */
    private function _getType($type, $column)
    {
        $size = $column->getSize();
        $columntype = $type;

        if ($type == "Numeric") {
            if ($size == 1) {
                $columntype = "Check";
            } else if ($size == 11) {
                $columntype = "Select";
            }
        } else if ($type == "Text") {
            if ($size > 255) {
                $columntype = "Textarea";
            }
            if (preg_match("#(pass|pwd)#i", $column->getName())) {
                $columntype = "Password";
            }
        }

        return $columntype;
    }

    /**
     * Return the Phalcon type (Numeric, Date, Text, ...) according to db type
     * @param string $type
     * @return string
     */
    private function _getBaseType($type)
    {
        $types = [
            \Phalcon\Db\Column::TYPE_INTEGER    => "Numeric",
            \Phalcon\Db\Column::TYPE_DATE       => "Date",
            \Phalcon\Db\Column::TYPE_VARCHAR    => "Text",
            \Phalcon\Db\Column::TYPE_DECIMAL    => "Numeric",
            \Phalcon\Db\Column::TYPE_DATETIME   => "Text",
            \Phalcon\Db\Column::TYPE_CHAR       => "Text",
            \Phalcon\Db\Column::TYPE_TEXT       => "Textarea",
            \Phalcon\Db\Column::TYPE_FLOAT      => "Numeric",
            \Phalcon\Db\Column::TYPE_BOOLEAN    => "Numeric",
            \Phalcon\Db\Column::TYPE_DOUBLE     => "Numeric",
            \Phalcon\Db\Column::TYPE_TINYBLOB   => "Textarea",
            \Phalcon\Db\Column::TYPE_BLOB       => "Textarea",
            \Phalcon\Db\Column::TYPE_MEDIUMBLOB => "Textarea",
            \Phalcon\Db\Column::TYPE_LONGBLOB   => "Textarea",
            \Phalcon\Db\Column::TYPE_BIGINTEGER => "Numeric",
            \Phalcon\Db\Column::TYPE_JSON       => "Textarea",
            \Phalcon\Db\Column::TYPE_JSONB      => "Textarea",
            //\Phalcon\Db\Column::TYPE_TIMESTAMP  => "",
            17                                  => "Numeric",
        ];

        return isset($types[$type]) ? $types[$type] : "Text";
    }

}
