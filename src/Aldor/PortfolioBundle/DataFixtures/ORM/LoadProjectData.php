<?php
// src/Aldor/PortfolioBundle/DataFixtures/ORM/LoadPostCategoryData.php
namespace Aldor\PortfolioBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Aldor\PortfolioBundle\Entity\Project;
use Symfony\Component\Finder\Finder;
use Application\Sonata\MediaBundle\Entity\Media as Media;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadProjectData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
      public function setContainer(ContainerInterface $container = null)
              {
                      $this->container = $container;
                  }
  public function load(ObjectManager $em)
    {
       $files = Finder::create()
            ->name('*.JPG')
           ->in(__DIR__.'/../data/files');

       $manager = $this->container->get('sonata.media.manager.media');

       foreach ($files as $pos => $file) {

      $media = new Media();
      $media->setBinaryContent($file);
      $media->setEnabled(true);
      $manager->save($media, 'portfolio', 'sonata.media.provider.image');
    }
      $pro = new Project();
      $pro->setTechnology($em->merge($this->getReference("Technology-web")));
      $pro->setName("Symfony2 Portfolio");
      $pro->setMedia($media);
      $pro->setDescription('aaa');
      $pro->setRawContent("

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ullamcorper varius urna,
          in ultrices arcu auctor sed. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
          Etiam dolor tellus, posuere at tincidunt at, consequat et mi. Curabitur accumsan aliquet interdum. Nullam lacus velit, varius non dictum in,
          interdum vel tortor. Maecenas eget quam lorem, vel aliquet tellus. Fusce vel diam diam, at vehicula lorem. Nunc consequat dolor vel ante
          accumsan auctor. Aenean euismod, quam sit amet mattis tincidunt, odio massa iaculis lectus, in consectetur quam sapien in lorem. Suspendisse blandi
          t feugiat ipsum vitae gravida. Maecenas et turpis odio. Vivamus nec velit ac purus vestibulum ullamcorper. Aliquam nisi tortor, viverra ut ullamcorper nec, cursus id felis. Sed lorem quam, vehicula in consequat
          id, auctor eget odio. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. ");
      $pro->setContent("

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ullamcorper varius urna,
          in ultrices arcu auctor sed. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
          Etiam dolor tellus, posuere at tincidunt at, consequat et mi. Curabitur accumsan aliquet interdum. Nullam lacus velit, varius non dictum in,
          interdum vel tortor. Maecenas eget quam lorem, vel aliquet tellus. Fusce vel diam diam, at vehicula lorem. Nunc consequat dolor vel ante
          accumsan auctor. Aenean euismod, quam sit amet mattis tincidunt, odio massa iaculis lectus, in consectetur quam sapien in lorem. Suspendisse blandi
          t feugiat ipsum vitae gravida. Maecenas et turpis odio. Vivamus nec velit ac purus vestibulum ullamcorper. Aliquam nisi tortor, viverra ut ullamcorper nec, cursus id felis. Sed lorem quam, vehicula in consequat
          id, auctor eget odio. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. ");
      $pro->setUrl("no");
      $pro->setDate(new \DateTime);
     $pro2 = new Project();
      $pro2->setTechnology($em->merge($this->getReference("Technology-asm")));
      $pro2->setName("Symfony2 Portfolio test");
      $pro2->setMedia($media);
      $pro2->setDescription('asfasfasf');
      $pro2->setRawContent("
sdf
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ullamcorper varius urna,
          in ultrices arcu auctor sed. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
          Etiam dolor tellus, posuere at tincidunt at, consequat et mi. Curabitur accumsan aliquet interdum. Nullam lacus velit, varius non dictum in,
          interdum vel tortor. Maecenas eget quam lorem, vel aliquet tellus. Fusce vel diam diam, at vehicula lorem. Nunc consequat dolor vel ante
          accumsan auctor. Aenean euismod, quam sit amet mattis tincidunt, odio massa iaculis lectus, in consectetur quam sapien in lorem. Suspendisse blandi
          t feugiat ipsum vitae gravida. Maecenas et turpis odio. Vivamus nec velit ac purus vestibulum ullamcorper. Aliquam nisi tortor, viverra ut ullamcorper nec, cursus id felis. Sed lorem quam, vehicula in consequat
          id, auctor eget odio. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. ");
      $pro2->setContent("
sdf
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ullamcorper varius urna,
          in ultrices arcu auctor sed. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
          Etiam dolor tellus, posuere at tincidunt at, consequat et mi. Curabitur accumsan aliquet interdum. Nullam lacus velit, varius non dictum in,
          interdum vel tortor. Maecenas eget quam lorem, vel aliquet tellus. Fusce vel diam diam, at vehicula lorem. Nunc consequat dolor vel ante
          accumsan auctor. Aenean euismod, quam sit amet mattis tincidunt, odio massa iaculis lectus, in consectetur quam sapien in lorem. Suspendisse blandi
          t feugiat ipsum vitae gravida. Maecenas et turpis odio. Vivamus nec velit ac purus vestibulum ullamcorper. Aliquam nisi tortor, viverra ut ullamcorper nec, cursus id felis. Sed lorem quam, vehicula in consequat
          id, auctor eget odio. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. ");
      $pro2->setUrl("no");
        $pro2->setDate(new \DateTime);
        $em->persist($pro2);
      $em->persist($pro);

    $em->flush();
  }

  public function getOrder()
  {
    return 7; // the order in which fixtures will be loaded
  }
}

