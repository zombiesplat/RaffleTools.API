<?php
namespace RaffleTools\Auth;

use Equip\Auth\RequestFilterInterface;
use Psr\Http\Message\ServerRequestInterface;

class RequestFilter implements RequestFilterInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return boolean TRUE if the request should require authentication,
     *         FALSE otherwise
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $uri = $request->getUri();
        if ($uri->getPath() == '/auth/register') {
            return false;
        }
        return true;
    }
}