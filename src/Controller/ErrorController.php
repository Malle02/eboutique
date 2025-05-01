<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ErrorController extends AbstractController
{
    public function show(Throwable $exception): Response
    {
        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500;
        
        if ($statusCode === 404) {
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig', [
                'status_code' => 404,
                'status_text' => 'Page non trouvée'
            ]);
        }
        
        return $this->render('bundles/TwigBundle/Exception/error.html.twig', [
            'status_code' => $statusCode,
            'status_text' => isset(Response::$statusTexts[$statusCode]) ? Response::$statusTexts[$statusCode] : 'Erreur'
        ]);
    }
}