<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LogoutHandler implements LogoutSuccessHandlerInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onLogoutSuccess(Request $request): Response
    {
        // Vérifiez si la déconnexion vient de l'admin
        if (str_contains($request->headers->get('referer', ''), '/admin')) {
            return new RedirectResponse($this->router->generate('app_login'));
        }

        // Sinon, redirigez vers la page d'accueil
        return new RedirectResponse($this->router->generate('home'));
    }
}