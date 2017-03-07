<?php

namespace De\Idrinth\EntityGenerator;

class Property {
    /**
     *
     * @var string[]
     */
    protected static $types = array(
        'tinyint' => 'int',
        'smallint' => 'int',
        'mediumint' => 'int',
        'int' => 'int',
        'bigint' => 'int',
        'bit' => 'int',
        'year' => 'int',
        'float' => 'float',
        'double' => 'float',
        'decimal' => 'float',
    );
    /**
     *
     * @var string
     */
    protected $name;
    /**
     *
     * @var string
     */
    protected $type;
    /**
     *
     * @var string
     */
    protected $target;
    /**
     *
     * @var boolean
     */
    protected $autoincrement;
    /**
     *
     */
    public function __construct() {
        $this->name = $this->name . '';
        $this->type = $this->type . '';
        $this->target = $this->target . '';
        $this->autoincrement = (bool) $this->autoincrement;
    }
    /**
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    /**
     *
     * @return string
     */
    public function getType() {
        if($this->getTarget()) {
            return $this->getTarget();
        }
        if(isset(self::$types[$this->type])) {
            return self::$types[$this->type];
        }
        return 'string';
    }
    /**
     *
     * @return string
     */
    public function getTarget() {
        return $this->target;
    }
    /**
     *
     * @return boolean
     */
    public function getAutoincrement() {
        return $this->autoincrement;
    }
}