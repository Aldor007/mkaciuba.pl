<?php
// src/Aldor/GalleryBundle/DataFixtures/ORM/LoadPostCategoryData.php
namespace Aldor\GalleryBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Aldor\GalleryBundle\Entity\PhotoCategory;
use Aldor\InftechBundle\Utils\Inftech as Inftech;
use Symfony\Component\Finder\Finder;
use Application\Sonata\MediaBundle\Entity\Media as Media;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
class LoadPhotoCategoryData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
      $design = new PhotoCategory();
      $design->setName('Grafika');
      $design->setSlug(Inftech::slugify('Grafika'));
      $design->setImage($medias[0]);
      $design->setGallery($em->merge($this->getReference('Gallery-1')));


      $design2 = new PhotoCategory();
      $design2->setName('Grafika2');
      $design2->setSlug(Inftech::slugify('Grafika2'));
      $design2->setImage($medias[1]);
      $design2->setGallery($em->merge($this->getReference('Gallery-1')));
 $design3 = new PhotoCategory();
      $design3->setName('Grafika4');
      $design3->setSlug(Inftech::slugify('Grafika4'));
      $design3->setImage($medias[3]);
      $design3->setGallery($em->merge($this->getReference('Gallery-1')));
 $design4 = new PhotoCategory();
      $design4->setName('Grafika3');
      $design4->setSlug(Inftech::slugify('Grafika3'));
      $design4->setImage($medias[2]);
      $design4->setGallery($em->merge($this->getReference('Gallery-1')));



    $programming = new PhotoCategory();
    $programming->setName('Programowanie');
    $programming->setSlug(Inftech::slugify('Programowanie'));
    $programming->setImage($medias[4]);
    $programming->setGallery($em->merge($this->getReference('Gallery-2')));

    $linux = new PhotoCategory();
    $linux->setName('Linux');
    $linux->setSlug(Inftech::slugify('Linux'));
    $linux->setImage($medias[5]);
    $linux->setGallery($em->merge($this->getReference('Gallery-2')));

      $em->persist($design);
    $em->persist($programming);
    $em->persist($linux);
    $em->persist($design2);
    $em->persist($design3);
    $em->persist($design4);

    $em->flush();

    $this->addReference('PhotoCategory-1', $design);
    $this->addReference('PhotoCategory-4', $design2);
    $this->addReference('PhotoCategory-6', $design3);
    $this->addReference('PhotoCategory-5', $design4);
    $this->addReference('PhotoCategory-2', $programming);
    $this->addReference('PhotoCategory-3', $linux);
      }

  public function getOrder()
  {
    return 9; // the order in which fixtures will be loaded
  }
}

