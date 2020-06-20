<?php

namespace App\EventSubscriber;

use App\Entity\BrowserStatistic;
use Doctrine\ORM\EntityManagerInterface;
use Sinergi\BrowserDetector\Browser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class BrowserStatisticsSubscriber implements EventSubscriberInterface
{
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var Browser
     */
    private $browserDetector;

    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager, Browser $browserDetector)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
        $this->browserDetector = $browserDetector;
    }

    public function onKernelRequest(RequestEvent $event, $eventName, $dispatcher)
    {
        $browser = $this->browserDetector->getName();
        if (!($browser == Browser::UNKNOWN || $this->browserDetector->isRobot() || $this->session->has(__CLASS__))) {
            $this->entityManager->persist(
                (new BrowserStatistic())
                ->setBrowser($browser)
                ->setIp($event->getRequest()->getClientIp())
                ->setCreatedAt(new \DateTime())
            );
            $this->entityManager->flush();
            $this->session->set(__CLASS__, true);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => 'onKernelRequest',
        ];
    }
}
