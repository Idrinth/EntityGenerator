<?php

use De\Idrinth\EntityGenerator\BaseEntity;

namespace De\Idrinth\EntityGenerator;

interface EntityHandler {

    /**
     * This loads data into the given entity
     * @param BaseEntity $entity
     * @return BaseEntity $entity
     */
    public static function load(BaseEntity $entity);

    /**
     * This stores a given entity in the database
     * @param BaseEntity $entity
     * @return BaseEntity $entity
     */
    public static function store(BaseEntity $entity);
}
