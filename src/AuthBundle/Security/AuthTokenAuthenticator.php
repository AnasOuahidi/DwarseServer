<?php
namespace AuthBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Http\HttpUtils;

class AuthTokenAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface {

    protected $httpUtils;

    public function __construct(HttpUtils $httpUtils) {
        $this->httpUtils = $httpUtils;
    }

    public function createToken(Request $request, $providerKey) {
        $authUrl = '/auth/authtokens';
        $signUrl = '/auth/users';
        if (($request->getMethod() === "POST" && $this->httpUtils->checkRequestPath($request, $authUrl)) || ($request->getMethod() === "POST" && $this->httpUtils->checkRequestPath($request, $signUrl))) {
            return;
        }
        $authTokenHeader = $request->headers->get('X-Auth-Token');
        if (!$authTokenHeader) {
            throw new BadCredentialsException('X-Auth-Token header est obligatoire');
        }
        return new PreAuthenticatedToken(
            'anon.',
            $authTokenHeader,
            $providerKey
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey) {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey) {
        if (!$userProvider instanceof AuthTokenUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of AuthTokenUserProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }
        $authTokenHeader = $token->getCredentials();
        $authToken = $userProvider->getAuthToken($authTokenHeader);

        if (!$authToken || !$userProvider->getUserByAuthToken($authToken)) {
            throw new BadCredentialsException('Invalid authentication token');
        }
        $user = $authToken->getUser();
        $pre = new PreAuthenticatedToken(
            $user,
            $authTokenHeader,
            $providerKey,
            $user->getRoles()
        );
        $pre->setAuthenticated(true);
        return $pre;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        throw $exception;
    }
}