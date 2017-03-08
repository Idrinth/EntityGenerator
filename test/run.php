<?php
/* 
 * this is a wrapper, so phpunit doesn't fail because of
 */

echo preg_match('/^7\.0/', PHP_VERSION)?1:0;