<?php
//consider this an example
use De\Idrinth\EntityGenerator\EntityGenerator;
require_once __DIR__ . DIRECTORY_SEPARATOR . (is_dir(__DIR__ . DIRECTORY_SEPARATOR . 'vendor')?'':'..' . DIRECTORY_SEPARATOR) . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$pdo = new PDO('mysql:host=localhost','root','');
$path = __DIR__ . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . '{{schema}}';
$gen = new EntityGenerator($pdo,$path,'Mine');
$gen->run(array('information_schema','mysql','performance_schema'));
