<?php

// src/Aldor/BlogBundle/DataFixtures/ORM/LoadTagData.php
namespace Aldor\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Aldor\BlogBundle\Entity\Tag;

class LoadTagData extends AbstractFixture implements OrderedFixtureInterface
{
    
   public function load(ObjectManager $em)
  {
    $design = new Tag();
    $design->setName('Grafika');
    $design->setCount(1);
    $design->addPost($em->merge($this->getReference("Post-1"))); 
    
    $programming = new Tag();
    $programming->setName('Programowanie');
    $programming->setCount(1);
    $programming->addPost($em->merge($this->getReference("Post-1")));
    
    $linux = new Tag();
    $linux->setName('Linux');
    $linux->setCount(1);
    $linux->addPost($em->merge($this->getReference("Post-1")));
    
    $cubie = new Tag();
    $cubie->setName('Cubieboard');
    $cubie->setCount(1);
    $cubie->addPost($em->merge($this->getReference("Post-2")));
  
  
    $em->persist($design);
    $em->persist($programming);
    $em->persist($linux);
    $em->persist($cubie);

    $em->flush();

    $this->addReference('Tag-design', $design);
    $this->addReference('Tag-programming', $programming);
    $this->addReference('Tag-linux', $linux);
    $this->addReference('Tag-cubieboard', $cubie);
  }

  public function getOrder()
  {
    return 4; // the order in which fixtures will be loaded
  }
}
