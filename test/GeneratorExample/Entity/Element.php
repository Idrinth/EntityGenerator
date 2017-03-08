<?php
namespace De\Idrinth\EntityGenerator\Test\GeneratorExample\Entity;
    use De\Idrinth\EntityGenerator\BaseEntity;
    use De\Idrinth\EntityGenerator\EntityHandler;
    /**
    * Automatically generated entity for
    * @table element
    * @database generator-example
    **/
class Element extends BaseEntity {
                        /**
            * @var element_list
                        * @column list
            **/
                protected $list;
                                    /**
                * @return ElementList                **/
                        public function getList() {
            $this->initEntity();
                            if(is_int($this->list)) {
                $this->list = Factory::provide(
                'De\Idrinth\EntityGenerator\Test\GeneratorExample\Entity\ElementList',
                $this->list
                );
                }
                        return $this->list;
            }
                                                                /**
                    * @param ElementList                    **/
                                public function setList(ElementList $param) {
                $this->initEntity();
                $this->list = $param;
                }
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
                                                /**
            * @var float
                        * @column double_test
            **/
                protected $doubleTest;
                                    /**
                * @return float                **/
                        public function getDoubleTest() {
            $this->initEntity();
                        return $this->doubleTest;
            }
                                                                /**
                    * @param float                    **/
                                public function setDoubleTest( $param) {
                $this->initEntity();
                $this->doubleTest = $param;
                }
                                                /**
            * @var float
                        * @column decimal_test
            **/
                protected $decimalTest;
                                    /**
                * @return float                **/
                        public function getDecimalTest() {
            $this->initEntity();
                        return $this->decimalTest;
            }
                                                                /**
                    * @param float                    **/
                                public function setDecimalTest( $param) {
                $this->initEntity();
                $this->decimalTest = $param;
                }
                        }