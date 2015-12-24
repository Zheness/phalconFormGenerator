<?php

class IndexController extends ControllerBase
{
    const BR = "<br/>";
    const TAB = "\t";

    public function indexAction()
    {
        echo "<pre>";
        $connection = new \Phalcon\Db\Adapter\Pdo\Mysql($this->config->database->toArray());
        $tables = $connection->listTables();
        foreach ($tables as $table) {

            // Write class Name
            $tablename = \Phalcon\Text::camelize($table);
            echo "class {$tablename}Form {" . self::BR;

            $columns = $connection->describeColumns($table);
            foreach ($columns as $column) {
                if ($column instanceof \Phalcon\Db\Column) {

                    // Escape if column is primary
                    if ($column->isPrimary())
                        continue;

                    // Write private method Name
                    $columnname = \Phalcon\Text::camelize($column->getName());
                    echo self::TAB . "private function _{$columnname}() {" . self::BR;

                    $columntype_base = $this->_getBaseType($column->getType());
                    $columntype = $this->_getType($columntype_base, $column);
                    echo self::TAB . self::TAB . "\$element = new \\Phalcon\\Forms\\Element\\{$columntype}(\"{$columnname}\");" . self::BR;
                    echo self::TAB . self::TAB . "\$element->setLabel(\"{$columnname}\");" . self::BR;

                    // Add validator on text fields
                    if ($columntype == "Text" && $column->getSize() > 0) {
                        echo self::TAB . self::TAB . "\$element->addValidator(new \\Phalcon\\Validation\\Validator\\StringLength([" . self::BR;
                        echo self::TAB . self::TAB . self::TAB . "\"max\" => {$column->getSize()}" . self::BR;
                        echo self::TAB . self::TAB . "]);" . self::BR;
                    }

                    // End method
                    echo self::TAB . self::TAB . "return \$element;" . self::BR;
                    echo self::TAB . "}" . self::BR;
                }
            }

            // Final method : construction of the form
            echo self::TAB . "public function setFields() {" . self::BR;
            foreach ($columns as $column) {
                if ($column instanceof \Phalcon\Db\Column) {
                    if ($column->isPrimary())
                        continue;
                    $columnname = \Phalcon\Text::camelize($column->getName());
                    echo self::TAB . self::TAB . "\$this->add(\$this->_{$columnname}());" . self::BR;
                }
            }
            echo self::TAB . "}" . self::BR;

            // End class
            echo "}" . self::BR . self::BR;
        }
        $this->view->disable();

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
