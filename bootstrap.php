<?php
/**
 * Boostrap the bare minimums
 *
 * @license http://opensource.org/licenses/MIT MIT
 */

require_once "vendor/autoload.php";
$injector = new Auryn\Injector();
$env = new Equip\Configuration\EnvConfiguration(__DIR__ . '/.env');
$env->apply($injector);
$database = new RaffleTools\Configuration\DatabaseConfiguration();
$database->apply($injector);
$entityManager = $injector->make(Doctrine\ORM\EntityManager::class);
