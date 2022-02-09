<?php

namespace App\EventListener;

use App\Kernel;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelEventSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    /**
     * Contrat d'interface. Doit être implémenté dans tout Event Subscriber.
     * @return \array[][]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            // Event kernel.exception
            KernelEvents::EXCEPTION => [
                ['displayKernelExceptionTriggered', 225],
                ['logKernelExceptionTriggered', 1],
            ],
            // Event kernel.finish_request
            KernelEvents::FINISH_REQUEST => [
                ['logIpAddress', 0],
            ]
        ];
    }

    /**
     * Catch kernel exception.
     * @param ExceptionEvent $event
     * @return void
     */
    public function displayKernelExceptionTriggered(ExceptionEvent $event)
    {
        // Customize your response object to display the exception details.
        $response = new Response();
        $response->setContent("
            <h1>Une erreur est survenue!</h1>
        ");
        $event->setResponse($response);
    }

    /**
     * Event kernel.request
     * @param RequestEvent $event
     * @return void
     */
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest()->getRealMethod();

        if ($request !== 'POST') {
            $event->setResponse(new Response('forbidden - accès interdit!', 403));
        }
    }

    /**
     * Only log exception.
     * @param ExceptionEvent $event
     * @return void
     */
    public function logKernelExceptionTriggered(ExceptionEvent $event)
    {
        $message = $event->getThrowable()->getMessage();
        file_put_contents(__DIR__ . '/ec-logs.log', $message, FILE_APPEND);
    }

    /**
     * Log ip address on request finished.
     * @param FinishRequestEvent $event
     * @return void
     */
    public function logIpAddress(FinishRequestEvent $event)
    {
        if (!$event->isMainRequest()){
            return;
        }

        $ipAddress = $event->getRequest()->getClientIp();
        $this->logger->info("Request finished {kernel.finish_request::logIpAddress()}", ['Request from' => $ipAddress]);
    }

}