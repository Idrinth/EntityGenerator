<?php

namespace De\Idrinth\EntityGenerator;

use PDO;

class EntityHandlerFactory
{
    /**
     *
     * @var EntityHandler
     */
    protected static $instance;

    /**
     *
     * @param PDO $database
     * @return EntityHandler
     */
    public static function init(PDO $database)
    {
        if (!self::$instance) {
            // @codeCoverageIgnoreStart
            self::$instance = new EntityHandler($database);
            // @codeCoverageIgnoreEnd
        }
        return self::get();
    }

    /**
     *
     * @return EntityHandler
     */
    public static function get()
    {
        return self::$instance;
    }

    /**
     * @return boolean
     */
    public static function reset()
    {
        if (self::$instance) {
            self::$instance = null;
        }
        return !self::$instance;
    }
}