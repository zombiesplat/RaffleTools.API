<?php
/**
 * Needed to run Doctrine2 command lines
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
require_once "bootstrap.php";
return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);