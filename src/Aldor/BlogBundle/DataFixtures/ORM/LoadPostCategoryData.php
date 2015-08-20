<?php
// src/Aldor/BlogBundle/DataFixtures/ORM/LoadPostCategoryData.php
namespace Aldor\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Aldor\BlogBundle\Entity\PostCategory;
use Aldor\InftechBundle\Utils\Inftech;
class LoadPostCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $em)
  {
      $design = new PostCategory();
    $design->setName('Grafika');
 $design->setSlug(Inftech::slugify('Grafika'));
    $programming = new PostCategory();
    $programming->setName('Programowanie');
    $programming->setSlug(Inftech::slugify('Programowanie'));
    $linux = new PostCategory();
    $linux->setName('Linux');
$linux->setSlug(Inftech::slugify('Linux'));
    $cubie = new PostCategory();
    $cubie->setName('Cubieboard');
    $cubie->setSlug(Inftech::slugify('Cubieboard'));
    $em->persist($design);
    $em->persist($programming);
    $em->persist($linux);
    $em->persist($cubie);

    $em->flush();

    $this->addReference('PostCategory-design', $design);
    $this->addReference('PostCategory-programming', $programming);
    $this->addReference('PostCategory-linux', $linux);
    $this->addReference('PostCategory-cubie', $cubie);
  }

  public function getOrder()
  {
    return 2; // the order in which fixtures will be loaded
  }
}

