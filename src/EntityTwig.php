<?php

namespace De\Idrinth\EntityGenerator;

use Twig_Extension;
use Twig_SimpleFilter;

class EntityTwig extends Twig_Extension {

    /**
     *
     * @param string $string
     * @return string
     */
    public static function toUpperCamelCase($string)
    {
        $parts = explode('_',
                         str_replace(array('/', '\\', '-', '.'), '_', $string));
        $formatted = array();
        foreach ($parts as $part)
        {
            if ($part)
            {
                $formatted[] = strtoupper($part{0}) . strtolower(substr($part, 1));
            }
        }
        return implode('', $formatted);
    }

    /**
     *
     * @param string $string
     * @return string
     */
    public static function toLowerCamelCase($string)
    {
        $uCC = self::toUpperCamelCase($string);
        $uCC{0} = strtolower($uCC{0});
        return $uCC;
    }

    /**
     *
     * @return Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('toLowerCamelCase',
                                  'De\Idrinth\EntityGenerator\EntityTwig::toLowerCamelCase'),
            new Twig_SimpleFilter('toUpperCamelCase',
                                  'De\Idrinth\EntityGenerator\EntityTwig::toUpperCamelCase'),
        );
    }

}
