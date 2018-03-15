<?php

namespace Vinorcola\ApiServerTools;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        if (!preg_match('/^\/api\//', $request->getPathInfo())) {
            return;
        }

        $exception = $event->getException();
        $status = $exception instanceof HttpExceptionInterface ?
            $exception->getStatusCode() :
            500;
        $headers = $exception instanceof HttpExceptionInterface ?
            $exception->getHeaders() :
            [];
        $response = new Response([], $exception->getMessage(), $status, $headers);
        $event->setResponse($response);
    }
}
