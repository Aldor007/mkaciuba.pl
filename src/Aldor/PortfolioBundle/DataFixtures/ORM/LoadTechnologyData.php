<?php
// src/Aldor/PortfolioBundle/DataFixtures/ORM/LoadPostCategoryData.php
namespace Aldor\PortfolioBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Aldor\PortfolioBundle\Entity\Technology;
use Aldor\InftechBundle\Utils\Inftech as Inftech;
class LoadTechnologyData extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $em)
  {
    $web = new Technology();
    $web->setName("Web");
    $web->setSlug(Inftech::slugify("web"));
    
    $cpp = new Technology();
    $cpp->setName("cpp");
    $cpp->setSlug(Inftech::slugify("cpp"));
    $asm = new Technology();
    $asm->setName("assembler");
    $asm->setSlug(Inftech::slugify("assembler"));

    $em->persist($web);
    $em->persist($cpp);
    $em->persist($asm);
   
    $em->flush();

    $this->addReference("Technology-web", $web);
    $this->addReference("Technology-cpp", $cpp);
    $this->addReference("Technology-asm", $asm);
    
  }

  public function getOrder()
  {
    return 5; // the order in which fixtures will be loaded
  }
}

