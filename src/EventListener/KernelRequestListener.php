<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class KernelRequestListener
{
    /**
     * Event kernel.request
     * @param RequestEvent $event
     * @return void
     */
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest()->getRealMethod();

        if ($request !== 'POST') {
            $event->setResponse(new Response('<h1>Type de requête non autorisée par le kernel</h1>', 403));
        }
    }

    /**
     * Event kernel.exception
     * @param ExceptionEvent $event
     * @return void
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $request = $event->getRequest()->getRealMethod();

        if ($request !== 'POST') {
            $event->setResponse(new Response('<h1>Type de requête non autorisée par le kernel</h1>', 403));
        }
    }
}