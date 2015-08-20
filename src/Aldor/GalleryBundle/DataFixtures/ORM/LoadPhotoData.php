<?php
// src/Aldor/GalleryBundle/DataFixtures/ORM/LoadPostData.php
namespace Aldor\GalleryBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Aldor\GalleryBundle\Entity\Photo;
use Symfony\Component\Finder\Finder;
use Application\Sonata\MediaBundle\Entity\Media as Media;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPhotoData extends AbstractFixture implements  ContainerAwareInterface, OrderedFixtureInterface
{
      public function setContainer(ContainerInterface $container = null)
              {
                      $this->container = $container;
                  }
  public function load(ObjectManager $em)
  {

       $files = Finder::create()
            ->name('*.*')
           ->in(__DIR__.'/../data/files');

       $manager = $this->container->get('sonata.media.manager.media');
        $medias = [];
       foreach ($files as $pos => $file) {
          $media = new Media();
          $media->setBinaryContent($file);
          $media->setEnabled(true);
          $manager->save($media, 'gallery', 'sonata.media.provider.image');
          $medias[] = $media;
        }


      for($i=1; $i<2; $i++)
      {
      $post = new Photo();
      $post->setCategory($em->merge($this->getReference("PhotoCategory-1")));
      $post->setTitle("testowe ".$i);
        $post->setImage($medias[0]);
     $post2 = new Photo();
      $post2->setCategory($em->merge($this->getReference("PhotoCategory-".rand(1,6))));
      $post2->setTitle("testowe ".$i);
        $post2->setImage($medias[1]);

      $em->persist($post2);
      $em->persist($post);
    $em->flush();
      $this->addReference('Photo-'.($i), $post);
      $this->addReference('Photo-'.($i-1), $post2);
  }
  }

  public function getOrder()
  {
    return 10; // the order in which fixtures will be loaded
  }
}

