<?php

namespace Aldor \GalleryBundle\Listener;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Acme\DemoBundle\Entity\Team;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonSerializationVisitor;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Add data after serialization
 *
 * @Service("acme.listener.serializationlistener")
 * @Tag("jms_serializer.event_subscriber")
 */
class SerializationListener implements EventSubscriberInterface
{
    protected $container;
    protected $request;

    public function __construct(ContainerInterface $container) // this is @service_container
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    static public function getSubscribedEvents()
    {
        return array(
            array('event' => 'serializer.post_serialize', 'class' => 'Aldor\GalleryBundle\Entity\Photo', 'method' => 'onPostSerialize'),
        );
    }


    public function onPostSerialize(ObjectEvent $event)
    {
        if (in_array('api', (array)$event->getContext()->attributes->get('groups'))) {
            return;
        }

        $object = $event->getObject();
        $deviceDetector = $this->container->get('mobile_detect.mobile_detector');
        $mediaFormat = 'big1300';
        if ($deviceDetector->isMobile() || $deviceDetector->isTablet()) {
            $mediaFormat = 'big300';
        }
        if ($deviceDetector->isTablet()) {
            $mediaFormat = 'medium';
        }


        $provider = $this->container->get($object->getImage()->getProviderName());
        $formatBig = $provider->getFormatName($object->getImage(), $mediaFormat);
        $formatLow = $provider->getFormatName($object->getImage(), 'big200');

        $event->getVisitor()->addData('lowsrc', $provider->generatePublicUrl($object->getImage(), $formatLow));
        $event->getVisitor()->addData('fullsrc', $provider->generatePublicUrl($object->getImage(), $formatBig));
    }

    public function onserializerpostserialize(ObjectEvent $event) {

        $this->onPostSerialize($event);
    }
}