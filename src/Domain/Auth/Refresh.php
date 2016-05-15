<?php

namespace RaffleTools\Domain\Auth;

use Equip\Adr\DomainInterface;
use Equip\Adr\PayloadInterface;
use Equip\Auth\AuthHandler;
use Equip\Auth\Token;

class Refresh implements DomainInterface
{
    /**
     * @var PayloadInterface
     */
    private $payload;

    /**
     * @param PayloadInterface $payload
     */
    public function __construct(PayloadInterface $payload)
    {
        $this->payload = $payload;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(array $input)
    {
        /* @var Token $token */
        $token = $input[AuthHandler::TOKEN_ATTRIBUTE];
        return $this->payload
            ->withStatus(PayloadInterface::OK)
            ->withOutput([
                'token' => $token->getToken(),
            ]);
    }
}
