<?php

namespace De\Idrinth\EntityGenerator\Test;

use De\Idrinth\EntityGenerator\EntityTwig;
use PHPUnit\Framework\TestCase;

class EntityTwigTest extends TestCase
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
}