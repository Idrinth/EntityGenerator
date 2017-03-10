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
        return self::$instance;
    }

    /**
     *
     * @return EntityHandler
     */
    public static function get()
    {
        return self::$instance;
    }
}