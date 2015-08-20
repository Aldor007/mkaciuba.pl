<?php
// src/Aldor/GalleryBundle/DataFixtures/ORM/LoadPostCategoryData.php
namespace Aldor\GalleryBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Aldor\GalleryBundle\Entity\Gallery;
use Aldor\InftechBundle\Utils\Inftech as Inftech;
class LoadGalleryData extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $em)
  {
    $gallery = new Gallery();
    $gallery->setName('fotografie');
    $gallery->setSlug(Inftech::slugify($gallery->getName()));

    $gallery2 = new Gallery();
    $gallery2->setName('blog');


    $em->persist($gallery);
    $em->persist($gallery2);

    $em->flush();

    $this->addReference('Gallery-1', $gallery);
    $this->addReference('Gallery-2', $gallery2);
      }

  public function getOrder()
  {
    return 8; // the order in which fixtures will be loaded
  }
}

