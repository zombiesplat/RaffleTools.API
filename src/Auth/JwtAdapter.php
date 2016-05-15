<?php
namespace RaffleTools\Auth;

use Doctrine\ORM\EntityManager;
use Equip\Auth\AdapterInterface;
use Equip\Auth\Credentials;
use Equip\Auth\Jwt\GeneratorInterface;
use Equip\Auth\Jwt\ParserInterface;
use Equip\Auth\Token;
use Equip\Auth\Exception\AuthException;
use Equip\Auth\Exception\InvalidException;
use Psr\Http\Message\ServerRequestInterface;
use RaffleTools\Entity\User;

/**
 * Credentials are not validated in this implementation.
 * For the purpose of this example project, the token is simply the id of
 * the "authenticated" user. The user is stored in the _SESSION for use in the
 * domain
 */
class JwtAdapter implements AdapterInterface
{
    /** @var GeneratorInterface */
    protected $generator;

    /** @var ParserInterface */
    protected $parser;

    /** @var ServerRequestInterface */
    protected $request;

    /** @var EntityManager */
    private $entityManager;

    /**
     * Adapter constructor.
     * @param GeneratorInterface $generatorInterface
     * @param ParserInterface $parserInterface
     * @param ServerRequestInterface $request
     * @param EntityManager $entityManager
     */
    public function __construct(
        GeneratorInterface $generatorInterface,
        ParserInterface $parserInterface,
        ServerRequestInterface $request,
        EntityManager $entityManager
    )
    {
        $this->generator = $generatorInterface;
        $this->parser = $parserInterface;
        $this->request = $request;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $token
     * @return Token
     */
    public function validateToken($token)
    {
        $parsed = $this->parser->parseToken((string)$token);
        $audience = $parsed->getMetadata('aud');
        if (is_array($audience) && in_array('refresh', $audience) && $this->request->getUri()->getPath() == '/auth/refresh') {
            // do like below and create a new token and a new jti token
            // but don't trust the scope, recreate that.
            //TODO: Refactor this in to a private method to avoid duplication
            $subject = $parsed->getMetadata('sub');
            $claims = [
                'sub' => $subject,
                'jti' => 'Unique ID Generator', //use the ramsey uuid generator or insert a record to a table and use that uuid
                'aud' => ['/', '/raffleitems', '/profile', '/events'],
                'refresh' => $this->generator->getToken([
                    'sub' => $subject,
                    'jti' => 'Unique ID Generator',
                    'aud' => ['refresh'],
                    'exp' => strtotime('+1 year'), // environment var
                ]),
            ];
            $token_str = $this->generator->getToken($claims);

            //store token_str and ijt's token string for ability to revoke via blacklist.

            // The best way to secure the token is to use cookies with HttpOnly and Secure ...
            // However I could very well encrypt the scope too.
            // Maybe the Domain/Auth/Login can handle that.
            return new Token($token_str, $claims);

        }
        // validation here
        // $parsed is an instance of \Equip\Auth\Token. You can call its
        // getMetadata() method here to get all metadata associated with the
        // token, such as a unique identifier for the user, in order to
        // validate the token.
//        $token = $parsed->getToken();
//        $meta = $parsed->getMetadata();
        // I think meta will be whatever is in the claims I sent earlier. like 'id' => ?

        // if token is in the blacklist table then throw exception
        return $parsed;
    }

    /**
     * @param Credentials $credentials
     * @return Token
     * @throws AuthException
     * @throws InvalidException
     */
    public function validateCredentials(Credentials $credentials)
    {
        $uri = $this->request->getUri();
        if ($uri->getPath() != '/auth/login') {
            throw new AuthException('This method only accepts Auth Tokens');
        }

        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $credentials->getIdentifier()
        ]);
        if ($user == null) {
            throw new AuthException('Invalid Credentials');
        }
        if (!password_verify($credentials->getPassword(), $user->getPasswordHash())) {
            throw new AuthException('Invalid Credentials');
        }
        $subject = 'user/' . $user->getUserId();
        $claims = [
            'sub' => 'user/' . $user->getUserId(),
            'jti' => 'Unique ID Generator',
            'aud' => ['/', '/raffleitems', '/profile', '/events'],
            'refresh' => $this->generator->getToken([
                'sub' => $subject,
                'jti' => 'Unique ID Generator',
                'aud' => ['refresh'],
                'exp' => strtotime('+1 year'),
            ]),
        ];
        $token_str = $this->generator->getToken($claims);

        //store token_str and ijt's token string for ability to revoke via blacklist.

        // The best way to secure the token is to use cookies with HttpOnly and Secure ...
        // However I could very well encrypt the scope too.
        // Maybe the Domain/Auth/Login can handle that.
        return new Token($token_str, $claims);
    }

}