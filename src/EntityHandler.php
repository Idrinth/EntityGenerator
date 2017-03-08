<?php

use De\Idrinth\EntityGenerator\BaseEntity;

namespace De\Idrinth\EntityGenerator;

interface EntityHandler {

    /**
     *
     * @param BaseEntity $entity
     * @return BaseEntity $entity
     */
    public static function load(BaseEntity $entity);

    /**
     *
     * @param BaseEntity $entity
     * @return BaseEntity $entity
     */
    public static function store(BaseEntity $entity);
}
