<?php

namespace De\Idrinth\EntityGenerator;

abstract class BaseEntity
{
    /**
     *
     * @var boolean
     */
    protected $entityInitialized = false;

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
    *
    */
    public function store()
    {
        if ($this->entityInitialized) {
            EntityHandlerFactory::get()->store($this);
        }
    }

    /**
     * 
     * @return boolean
     */
    public function getEntityInitialized()
    {
        return $this->entityInitialized;
    }


}