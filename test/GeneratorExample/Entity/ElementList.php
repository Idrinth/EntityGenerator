<?php
namespace De\Idrinth\EntityGenerator\Test\GeneratorExample\Entity;
    use De\Idrinth\EntityGenerator\BaseEntity;
    use De\Idrinth\EntityGenerator\EntityHandler;
    /**
    * Automatically generated entity for
    * @table element_list
    * @database generator-example
    **/
class ElementList extends BaseEntity {
                        /**
            * @var string
                        * @column name
            **/
                protected $name;
                                    /**
                * @return string                **/
                        public function getName() {
            $this->initEntity();
                        return $this->name;
            }
                                                                /**
                    * @param string                    **/
                                public function setName( $param) {
                $this->initEntity();
                $this->name = $param;
                }
                        }