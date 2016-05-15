<?php
namespace RaffleTools\Configuration;

use Aura\Filter\Locator\SanitizeLocator;
use Aura\Filter\Locator\ValidateLocator;
use Aura\Filter\Spec\SanitizeSpec;
use Aura\Filter\Spec\ValidateSpec;
use Aura\Input\Builder;
use Aura\Input\BuilderInterface;
use Aura\Input\FilterInterface;
use Auryn\Injector;
use Doctrine\ORM\EntityManager;
use Equip\Configuration\ConfigurationInterface;
use RaffleTools\Domain\Input\Filter;
use RaffleTools\Domain\Input\Validate\FilterVar as Validate_FilterVar;
use RaffleTools\Domain\Input\Sanitize\FilterVar as Sanitize_FilterVar;
use RaffleTools\Domain\Input\Validate\CheckUnique as Validate_CheckUnique;

class DiConfiguration implements ConfigurationInterface
{

    public function apply(Injector $injector)
    {
        $sanitizeFactories = [
            'filterVar' => function () {
                return new Sanitize_FilterVar();
            },
        ];
        $sanitizeLocator = new SanitizeLocator($sanitizeFactories);
        $sanitizeSpec = new SanitizeSpec($sanitizeLocator);
        $injector->share($sanitizeSpec);

        $validateFactories = [
            'filterVar' => function () {
                return new Validate_FilterVar();
            },
            'checkUnique' => function () use ($injector) {
                return new Validate_CheckUnique($injector->make(EntityManager::class));
            },
        ];
        $validateLocator = new ValidateLocator($validateFactories);
        $validateSpec = new ValidateSpec($validateLocator);

        $injector->share($validateSpec);
        $injector->alias(BuilderInterface::class, Builder::class);
        $injector->alias(FilterInterface::class, Filter::class);

    }
}