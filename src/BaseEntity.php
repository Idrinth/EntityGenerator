<?php

namespace De\Idrinth\EntityGenerator;

abstract class BaseEntity
{
    /**
     *
     * @var boolean
     */
    public $entityInitialized = false;

    /**
     * @var int
     * @column aid
     * */
    protected $aid;

    /**
     *
     * @param int $aid
     */
    public function __construct($aid = null)
    {
        if ((int) $aid) {
            $this->aid = (int) $aid;
        }
    }

    /**
     * @return int
     * */
    public function getAid()
    {
        return $this->aid;
    }
}