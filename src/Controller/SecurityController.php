<?php

namespace App\Controller;

use App\Security\TokenAuthenticator;
use App\Security\UserProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    private const LOGIN_REDIRECT_ROUTE = 'admin_default';
    private const LOGOUT_REDIRECT_ROUTE = 'security_login';

    /**
     * @Route("/authorize", name="security_authorize")
     */
    public function authorize(Request $request, UserProvider $provider): Response
    {
        $token = $request->get(TokenAuthenticator::COOKIE_NAME);

        if (!$provider->isValid($token)) {
            $this->addFlash('warning', 'Please login to continue.');
            $this->redirect($this->generateUrl('security_login'));
        }

        $response = $this->redirect($this->generateUrl(self::LOGIN_REDIRECT_ROUTE));
        $response->headers->setCookie(
            Cookie::create(TokenAuthenticator::COOKIE_NAME)
                ->withValue($token)
                ->withSecure(true)
        );

        return $response;
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(): Response
    {
        if ($this->getUser() !== null) {
            $this->redirect($this->generateUrl(self::LOGIN_REDIRECT_ROUTE));
        }

        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(): RedirectResponse
    {
        $response = $this->redirect($this->generateUrl(self::LOGOUT_REDIRECT_ROUTE));
        $response->headers->clearCookie(TokenAuthenticator::COOKIE_NAME);

        return $response;
    }
}