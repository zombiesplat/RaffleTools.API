<?php

/**
 * Define routes
 * Define the middleware queue
 *
 * @license http://opensource.org/licenses/MIT MIT
 */

require_once __DIR__ . '/../vendor/autoload.php';
/** @var \Equip\Application $app */
Equip\Application::build()
    ->setConfiguration([
        new Equip\Configuration\EnvConfiguration(__DIR__ . '/../.env'),
        RaffleTools\Configuration\DatabaseConfiguration::class,
        Equip\Configuration\AurynConfiguration::class,
        RaffleTools\Configuration\DiConfiguration::class,
        Equip\Configuration\DiactorosConfiguration::class,
        Equip\Configuration\PayloadConfiguration::class,
        Equip\Configuration\RelayConfiguration::class,
        RaffleTools\Configuration\AuthConfiguration::class,
        Equip\Configuration\WhoopsConfiguration::class,
    ])
    ->setMiddleware([
        Relay\Middleware\ResponseSender::class,
        Equip\Handler\ExceptionHandler::class,
        Equip\Handler\DispatchHandler::class,
        Relay\Middleware\JsonContentHandler::class,
        Relay\Middleware\FormContentHandler::class,
        Equip\Auth\AuthHandler::class,
        Equip\Handler\ActionHandler::class,
    ])
    ->setRouting(function (Equip\Directory $directory) {
        return $directory
            ->post('/auth/login', RaffleTools\Domain\Auth\Login::class)
            ->get('/auth/refresh', RaffleTools\Domain\Auth\Refresh::class)
            ->post('/auth/register', RaffleTools\Domain\Auth\Register::class)
            ->post('/client', RaffleTools\Domain\Client\PostClient::class)
            ->patch('/client/{id}', RaffleTools\Domain\Client\PatchClient::class)
            ->get('/client/{id}', RaffleTools\Domain\Client\GetClient::class)
            ->get('/raffleitem/{id}', RaffleTools\Domain\RaffleItem\GetRaffleItem::class)
            ->post('/raffleitem', RaffleTools\Domain\RaffleItem\PostRaffleItem::class); // End of routing
    })
    ->run();