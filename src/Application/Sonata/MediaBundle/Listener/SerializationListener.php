<?php
namespace Application\Sonata\MediaBundle\Listener;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GraphNavigator;

/**
 *  Add data after serialization
 * 
 *  @Service("application.listener.serializationlistener")
 *  @Tag("jms_serializer.event_subscriber")
 * */
class SerializationListener implements EventSubscriberInterface
{

    /**
     *      * @inheritdoc
     *           */
    static public function getSubscribedEvents()
    {
    
        return array(
            array('event' => 'serializer.post_serialize', 'class' => 'Application\Sonata\MediaBundle\Entity\Media', 'method' => 'onPostSerialize'),
                            );
    }

    public function onPostSerialize(ObjectEvent $event)
    {
            global $kernel;
            $imageProvider = $kernel->getContainer()->get('sonata.media.provider.image');
            $media = $event->getObject();
            $visitor = $event->getVisitor();
            $event->getVisitor()->addData('url', $imageProvider->generatePublicUrl($media, 'reference'));
            $visitor->addData('cdn_is_flushable', $media->getCdnIsFlushable());
            $visitor->addData('cdn_status', $media->getCdnStatus());
            $visitor->addData('cdn_flush_at', $media->getCdnFlushAt());
            $visitor->addData('binary_content', $media->getBinaryContent());
            $visitor->addData('previous_provider_reference', $media->getProviderReference());
            $visitor->addData('length', $media->getLength());
            $visitor->addData('id', $media->getId());
            $visitor->addData('enabled', $media->getEnabled());
        }
}
