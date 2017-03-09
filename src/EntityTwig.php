<?php

namespace De\Idrinth\EntityGenerator;

use Twig_Extension;
use Twig_SimpleFilter;

class EntityTwig extends Twig_Extension
{
    /**
     *
     * @return Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        $handler = new EntityNameHandler();
        return array(
            $this->getFilter($handler, 'toLowerCamelCase'),
            $this->getFilter($handler, 'toUpperCamelCase')
        );
    }

    /**
     * @param EntityNameHandler $handler
     * @param string $name
     * @return Twig_SimpleFilter
     */
    protected function getFilter($handler, $name)
    {
        return new Twig_SimpleFilter($name, array($handler, $name));
    }
}