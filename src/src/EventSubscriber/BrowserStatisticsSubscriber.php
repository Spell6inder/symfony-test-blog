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

    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function onKernelRequest(RequestEvent $event, $eventName, $dispatcher)
    {
        if (!$this->session->has(__CLASS__)) {
            $browser = (new \DeviceDetector\DeviceDetector($event->getRequest()->headers->get('User-Agent')));
            $browser->parse();
            $this->entityManager->persist(
                (new BrowserStatistic())
                ->setBrowser($browser->getClient('name'))
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
