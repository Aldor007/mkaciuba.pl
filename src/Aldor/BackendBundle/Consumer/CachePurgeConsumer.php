<?php
namespace Aldor\BackendBundle\Consumer;

use Sonata\NotificationBundle\Model\MessageInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Sonata\NotificationBundle\Exception\InvalidParameterException;
use Sonata\NotificationBundle\Consumer\ConsumerInterface;   
use Sonata\NotificationBundle\Consumer\ConsumerEvent;   



class CachePurgeConsumer implements ConsumerInterface
{

    protected $fosCacheManager;
    protected $cloudFlare;

    public function __construct($fosCacheManager, $cloudFlare) {
        $this->fosCacheManager = $fosCacheManager;
        $this->cloudFlare = $cloudFlare;

    }

    

    public function process(ConsumerEvent $event)
    {
        $message = $event->getMessage();
        $url = $message->getValue('url');
        $headers = $message->getValue('headers');

        $this->fosCacheManager->invalidatePath($url, $headers);
        $this->cloudFlare->api('zone_file_purge', array('z'=>'mkaciuba.pl', 'url'=>'https://mkaciuba.pl'.$url));
        $this->cloudFlare->api('zone_file_purge', array('z'=>'mkaciuba.pl', 'url'=>'http://mkaciuba.pl'.$url));

    }

}