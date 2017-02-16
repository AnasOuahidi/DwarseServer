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
        $confirmUrl = '/auth/authtokens';
        $loginUrl = '/auth/login';
        $signUrl = '/auth/users';
        $imgLogoUrl = '/image/logo';
        $imgHeartUrl = '/image/heart';
        if (($request->getMethod() === "POST" && $this->httpUtils->checkRequestPath($request, $loginUrl))
            || ($request->getMethod() === "POST" && $this->httpUtils->checkRequestPath($request, $confirmUrl))
            || ($request->getMethod() === "POST" && $this->httpUtils->checkRequestPath($request, $signUrl))
            || ($request->getMethod() === "GET" && $this->httpUtils->checkRequestPath($request, $imgLogoUrl))
            || ($request->getMethod() === "GET" && $this->httpUtils->checkRequestPath($request, $imgHeartUrl))) {
            return;
        }
        $authTokenHeader = $request->headers->get('X-Auth-Token');
        if (!$authTokenHeader) {
            throw new BadCredentialsException('Le token d\'authentification est obligatoire');
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
            throw new BadCredentialsException('token d\'authentification incorrecte');
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