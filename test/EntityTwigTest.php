<?php

namespace De\Idrinth\EntityGenerator\Test;

use De\Idrinth\EntityGenerator\EntityTwig;

class EntityTwigTest extends AbstractTestCase
{

    /**
     *
     */
    public function testOnlyProvidesFilters()
    {
        $extension = new EntityTwig();
        $this->assertCount(2, $extension->getFilters());
        $this->assertCount(0, $extension->getFunctions());
        $this->assertCount(0, $extension->getTokenParsers());
        $this->assertCount(0, $extension->getOperators());
        $this->assertCount(0, $extension->getNodeVisitors());
    }

    /**
     * @depends testOnlyProvidesFilters
     */
    public function testFiltersAreFilters()
    {
        $extension = new EntityTwig();
        foreach ($extension->getFilters() as $filter) {
            $this->assertInstanceOf('Twig_SimpleFilter', $filter);
        }
    }
}