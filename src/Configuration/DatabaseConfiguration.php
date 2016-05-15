<?php
namespace RaffleTools\Configuration;

use Auryn\Injector;
use Doctrine\Common\Persistence\ObjectManager;
use Equip\Configuration\ConfigurationInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Equip\Env;
use Ramsey\Uuid\Doctrine\UuidType;
use Doctrine\DBAL\Types\Type as Dbal_Type;

class DatabaseConfiguration implements ConfigurationInterface
{

    public function apply(Injector $injector)
    {
        /** @var Env $env */
        $env = $injector->make(Env::class);
        $paths = array(__DIR__ . '/../');
        $isDevMode = isset($env['DEVELOPMENT']);

        // the connection configuration
        $dbParams = array(
            'driver' => 'pdo_mysql',
            'host' => $env['DB_HOST'],
            'user' => $env['DB_USER'],
            'password' => $env['DB_PASS'],
            'dbname' => $env['DB_NAME'],
        );

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        $entityManager = EntityManager::create($dbParams, $config);
        Dbal_Type::addType('uuid', UuidType::class);
        $entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('uuid', 'uuid');
        $injector->share($entityManager);

        $injector->alias(ObjectManager::class, EntityManager::class);
    }
}