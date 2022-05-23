<?php

namespace Kriss\WebmanAuth\Authentication\Method;

use Kriss\WebmanAuth\Interfaces\AuthenticationMethodInterface;
use Kriss\WebmanAuth\Interfaces\IdentityInterface;
use Kriss\WebmanAuth\Interfaces\IdentityRepositoryWithTokenInterface;
use Webman\Http\Request;

class RequestHeaderMethod implements AuthenticationMethodInterface
{
    protected IdentityRepositoryWithTokenInterface $identityRepository;
    protected string $name = 'X-Api-Key';
    protected ?string $tokenType = null;

    public function __construct(IdentityRepositoryWithTokenInterface $identity, array $config = [])
    {
        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }
        $this->identityRepository = $identity;
    }

    /**
     * @inheritDoc
     */
    public function authenticate(Request $request): ?IdentityInterface
    {
        if ($token = $request->header($this->name)) {
            return $this->identityRepository->findIdentityByToken($token, $this->tokenType);
        }

        return null;
    }
}