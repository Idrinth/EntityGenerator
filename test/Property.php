<?php
namespace De\Idrinth\EntityGenerator\Test;

use De\Idrinth\EntityGenerator\Property as PropertyImplementation;

class Property extends PropertyImplementation{
    public function __construct($name,$type,$target,$autoincrement)
    {
        $this->name = $name;
        $this->type = $type;
        $this->target = $target;
        $this->autoincrement = $autoincrement;
        parent::__construct();
    }
}