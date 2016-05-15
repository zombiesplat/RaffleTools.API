<?php
namespace RaffleTools\Configuration;

use Auryn\Injector;
use Equip\Configuration\ConfigurationInterface;

use Equip\Auth\Token\ExtractorInterface as TokenExtractorInterface;
use Equip\Auth\Token\HeaderExtractor as TokenHeaderExtractor;

use Equip\Auth\Credentials\ExtractorInterface as CredentialsExtractorInterface;
use Equip\Auth\Credentials\BodyExtractor as CredentialsBodyExtractor;

use Equip\Auth\AdapterInterface;
use RaffleTools\Auth\JwtAdapter;

use Equip\Auth\RequestFilterInterface;
use RaffleTools\Auth\RequestFilter;


class AuthConfiguration implements ConfigurationInterface
{
    /**
     * Applies a configuration set to a dependency injector.
     *
     * @param Injector $injector
     */
    public function apply(Injector $injector)
    {
        // JWT
        $injector->define(
            'Equip\\Auth\\Jwt\\Configuration',
            [
                //Todo: Replace these with Environment Variables.
                ':publicKey' => 'uhklzsdv89ywrahu',
                ':ttl' => 3600, // in seconds, e.g. 1 hour
                ':algorithm' => 'HS256',
            ]
        );
        $injector->alias(
            'Equip\\Auth\\Jwt\\GeneratorInterface',
            'Equip\\Auth\\Jwt\\FirebaseGenerator'
        );
        $injector->alias(
            'Equip\\Auth\\Jwt\\ParserInterface',
            'Equip\\Auth\\Jwt\\FirebaseParser'
        );

        // Tokens
        $injector->alias(
            TokenExtractorInterface::class,
            TokenHeaderExtractor::class
        );
        $injector->define(
            TokenHeaderExtractor::class,
            [':header' => 'Bearer']
        );

        // Credentials
        $injector->alias(
            CredentialsExtractorInterface::class,
            CredentialsBodyExtractor::class //default constructor makes it want to see "username" and "password" passed in
        );

        // Adapter
        $injector->alias(
            AdapterInterface::class,
            JwtAdapter::class
        );

        // Determine if Auth is needed
        $injector->alias(
            RequestFilterInterface::class,
            RequestFilter::class
        );
    }


}