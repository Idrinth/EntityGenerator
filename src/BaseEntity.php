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
    * @return boolean
    */
    public function store()
    {
        if (!$this->entityInitialized && $this->aid) {
            // @codeCoverageIgnoreStart
            $this->initEntity();
            // @codeCoverageIgnoreEnd
        }
        $ret = EntityHandlerFactory::get()->store($this);
        if (!$this->aid && is_numeric($ret)) {
            $this->aid = $ret;
        }
        return (bool) $ret;
    }

    /**
     *
     * @return boolean
     */
    public function isEntityInitialized()
    {
        return $this->entityInitialized;
    }

    /**
     *
     */
    abstract protected function initEntity();

}