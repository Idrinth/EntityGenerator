<?php
namespace De\Idrinth\EntityGenerator\Test\Test\Entity;
use De\Idrinth\EntityGenerator\BaseEntity;
use De\Idrinth\EntityGenerator\EntityHandlerFactory;

/**
* Automatically generated entity for
* @table element
* @database test
**/
class Element extends BaseEntity {

    /**
    * @var int
    * @autoincrement
    * @column aid
    **/
    protected $aid;

    /**
    * @return int
    **/
    public function getAid() {
        return $this->aid;
    }

    /**
    * @var string
    * @column name
    **/
    protected $name;

    /**
    * @return string
    **/
    public function getName() {
        $this->initEntity();
        return $this->name;
    }

    /**
    * @param string
    **/
    public function setName($param) {
        $this->initEntity();
        $this->name = $param;
    }

    /**
    *
    */
    protected function initEntity()
    {
        if ($this->aid && !$this->entityInitialized) {
            $this->entityInitialized = true;
            EntityHandlerFactory::get()->load($this);
        }
    }
}