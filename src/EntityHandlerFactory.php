<?php

namespace De\Idrinth\EntityGenerator;

use PDO;

class EntityHandlerFactory
{
    /**
     *
     * @var string
     */
    protected static $class = 'De\Idrinth\EntityGenerator\EntityHandler';

    /**
     *
     * @param PDO $database
     * @return EntityHandler
     */
    public static function init(PDO $database)
    {
        if (!isset($GLOBALS[self::$class])) {
            // @codeCoverageIgnoreStart
            $GLOBALS[self::$class] = new EntityHandler($database);
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
        return isset($GLOBALS[self::$class])?$GLOBALS[self::$class]:null;
    }

    /**
     * @return boolean
     */
    public static function reset()
    {
        if(isset($GLOBALS[self::$class])) {
            unset($GLOBALS[self::$class]);
        }
        return !isset($GLOBALS[self::$class]);
    }
}