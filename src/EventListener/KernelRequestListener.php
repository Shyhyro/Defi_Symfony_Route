<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class KernelRequestListener
{
    /**
     * Catch all exceptions.
     * @param ExceptionEvent $event
     * @return void
     */
    public function onKernelException(ExceptionEvent $event)
    {
        // Get the exception object from the received event.
        $exception = $event->getThrowable();
        $message = sprintf(
            'L\'erreur est: %s avec le code d\'erreur: %s',
            $exception->getMessage(),
            $exception->getCode()
        );

        // Customize your response object to display the exception details.
        $response = new Response();
        $response->setContent("
            <h1>Une erreur est survenue!</h1>
            <h2>Contenu de l'erreur</h2>
            <div>$message</div>
        ");

        // Send the modified response object to display the exception details.
        $event->setResponse($response);
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$domain) {
            $event->setResponse(new Response('Not found!', 404));
        }
    }
}