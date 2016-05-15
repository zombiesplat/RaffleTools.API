<?php

namespace RaffleTools\Domain\Auth;

use Equip\Adr\DomainInterface;
use Equip\Adr\PayloadInterface;
use Equip\Auth\Exception\AuthException;
use Equip\Auth\AuthHandler;
use Equip\Auth\Token;

class Login implements DomainInterface
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
        $subject = $token->getMetadata('sub');
        $parts = explode('/', $subject);
        if (count($parts) != 2 && $parts[0] != 'user') {
            throw new AuthException;
        }
        return $this->payload
            ->withStatus(PayloadInterface::OK)
            ->withOutput([
                'token' => $token->getToken(),
            ]);
    }
}
