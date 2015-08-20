<?php
// src/Aldor/InftechBundle/DataFixtures/ORM/LoadPostCategoryData.php
namespace Aldor\InftechBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{


    private $container;

    public function setContainer(ContainerInterface $container = null) {
                $this->container = $container;
      }

  public function load(ObjectManager $em)
  {
      $userManager = $this->container->get('fos_user.user_manager');
      $factory = $this->container->get('security.encoder_factory');
 /** @var $manager \FOS\UserBundle\Doctrine\UserManager */
        $manager = $this->container->get('fos_user.user_manager');
    $aldor = $manager->createUser();
    $aldor->setUsername("Aldor");
    $aldor->setPlainPassword("Aldor");
    $aldor->setEmail("admin");
    $aldor->setEnabled(true);
    $aldor->setRoles(array('ROLE_SUPER_ADMIN'));
    $encoder = $factory->getEncoder($aldor);
    $password = $encoder->encodePassword('Aldor', $aldor->getSalt());
    $aldor->setPassword($password);


    $userManager->updateUser($aldor, true);
$em->persist($aldor);
    $em->flush();
    $this->addReference('User-aldor', $aldor);
  }

  public function getOrder()
  {
    return 1; // the order in which fixtures will be loaded
  }
}

