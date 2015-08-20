<?php
namespace Aldor\BackendBundle\Consumer;

use Sonata\NotificationBundle\Model\MessageInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Sonata\NotificationBundle\Exception\InvalidParameterException;
use Sonata\NotificationBundle\Consumer\ConsumerInterface;   
use Sonata\NotificationBundle\Consumer\ConsumerEvent;   

use Aldor\GalleryBundle\Entity\Photo;
use Aldor\GalleryBundle\Entity\PhotoCategory;
use Application\Sonata\MediaBundle\Entity\Media as Media;
use Aldor\BackendBundle\Utils\ExtractZip;

class BatchPhotoConsumer implements ConsumerInterface
{

    protected $logger;
    protected $enityManager;

     /**
     * @param \Symfony\Component\HttpKernel\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger, $entity)
    {
        $this->logger = $logger;
        if ($entity->isOpen()) {

        $this->enityManager = $entity;
        } else {
            $this->enityManager = $entity->create($entity->getConnection(), $entity->Configuration());
        }
    }
    protected function addCategory($message)
    {
        $categoryName = $message->getValue('categoryName');
        $gallery = $message->getValue('gallery');
        $filePath = $message->getValue('filePath');
        $donwnloadable = $message->getValue('donwnloadable');
        $prefixNameForm = $message->getValue('prefix_name');


        $category = new PhotoCategory();
        $category->setName($categoryName);
        $galleries = $this->enityManager->getRepository('AldorGalleryBundle:Gallery')->findAll();
        foreach ($galleries as $gal) {
            if ($gal->getName() == $gallery) {
                $category->setGallery($gal);
                break;
            }
        }

        $zip = new ExtractZip($filePath);
        $files = $zip->extract();
        if ($donwnloadable) {

            $zipFile = new Media();
            $zipFile->setBinaryContent($zip->getZipPath());
            $zipFile->setEnabled(true);
            $zipFile->setContext('default');
            $zipFile->setProviderName('sonata.media.provider.file');
            $category->setZip($zipFile);
        }
        $this->enityManager->persist($category);
        $prefixName = $categoryName;
        if ($prefixNameForm && $prefixNameForm !='') {
            $prefixName = $prefixNameForm;
        }

        $counter = 0;
        foreach ($files as $pos => $file) {
            $title = explode('/', $pos);
            $fileName = $title[count($title) - 1];
            $fileName = str_replace('DSC_', '', $fileName);
            $title = $prefixName.'-'.explode('.', $fileName)[0];
            $photo = new Photo();
            $photo->setTitle($title);
            $media = new Media();
            $media->setBinaryContent($file);
            $media->setEnabled(true);
            $media->setContext('gallery');
            $media->setName($categoryName.'-'.$fileName);

            $photo->setImage($media);
            $photo->addCategory($category);

            // if (count($photo->getCategories()) == 0) {
            //     print_r("jksdrgt-------------");
            //     throw "err";
            //     return 12313;
            // }

            $this->enityManager->persist($photo);

        }
        $this->enityManager->flush();
        $zip->clean();

    }
    protected function addPhotosToCategory($message) {

        $categoryName = $message->getValue('categoryName');
        $filePath = $message->getValue('filePath');
        
        $category = $this->enityManager->getRepository('AldorGalleryBundle:PhotoCategory')->findOneByName($categoryName);
        $zip = new ExtractZip($filePath);
        $files = $zip->extract();

        
        $counter = 0;
        $maxSequence = $this->enityManager->getRepository('AldorGalleryBundle:Photo')->getMaxSequenceByCategory($category->getId());
        foreach ($files as $pos => $file) {
             $title = explode('/', $pos);
            $fileName = $title[count($title) - 1];
            $fileName = str_replace('DSC_', '', $fileName);
            $title = $categoryName.'-'.explode('.', $fileName)[0];
            $photo = new Photo();
            $photo->setTitle($title);
            $media = new Media();
            $media->setBinaryContent($file);
            $media->setEnabled(true);
            $media->setContext('gallery');
            $media->setName($categoryName.'-'.$fileName);

            $photo->setImage($media);
            $photo->addCategory($category);
            $photo->setSequence($maxSequence++);
            $this->enityManager->persist($photo);

        }
    $this->enityManager->flush();
    $zip->clean();

    }

    public function process(ConsumerEvent $event)
    {
        $message = $event->getMessage();
        $this->logger->info("BatchPhotoConsumer: processing ");
        if ($message->getValue('type') == 'photos') {
            $this->addPhotosToCategory($message);
        } else {
            $this->addCategory($message);
        }


    }

}