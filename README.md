# EntityGenerator
Generates entities from a given database and provides optional handling of them.

## Checks

Travis php 5.3-7.1:
![Travis Status](https://travis-ci.org/Idrinth/EntityGenerator.svg?branch=master)

Codacy:
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/7e300b0906044c91a1b3bcf592dcac22)](https://www.codacy.com/app/Idrinth/EntityGenerator?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Idrinth/EntityGenerator&amp;utm_campaign=Badge_Grade)
[![Codacy Badge](https://api.codacy.com/project/badge/Coverage/7e300b0906044c91a1b3bcf592dcac22)](https://www.codacy.com/app/Idrinth/EntityGenerator?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Idrinth/EntityGenerator&amp;utm_campaign=Badge_Coverage)

Codeclimate:
[![Issue Count](https://lima.codeclimate.com/github/Idrinth/EntityGenerator/badges/issue_count.svg)](https://lima.codeclimate.com/github/Idrinth/EntityGenerator)
[![Code Climate](https://lima.codeclimate.com/github/Idrinth/EntityGenerator/badges/gpa.svg)](https://lima.codeclimate.com/github/Idrinth/EntityGenerator)

## Example

```php
<?php
//generate entities for a schema named test and a schema named tester
//existance an autoloader is expected
$generator =  new \De\Idrinth\EntityGenerator\EntityGenerator(
                new \PDO('mysql:host:localhost', 'root', ''),
                __DIR__.DIRECTORY_SEPARATOR.'{{schema}}',
                'De\Idrinth\EntityGenerator\Test'
            );
$object->run(array('test','tester'));
```