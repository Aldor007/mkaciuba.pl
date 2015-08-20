<?php
// src/Aldor/BlogBundle/DataFixtures/ORM/LoadPostData.php
namespace Aldor\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Aldor\BlogBundle\Entity\Post;
use Symfony\Component\Finder\Finder;
use Application\Sonata\MediaBundle\Entity\Media as Media;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPostData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
      $manager->save($media, 'blog', 'sonata.media.provider.image');
    }

      for($i=0; $i<52; $i=$i+3)
      {
      $post = new Post();
      $post->setCategory($em->merge($this->getReference("PostCategory-design")));
      $post->setCommentCount(0);
      $post->setTitle("o autorze".$i);
      $post->setContent("

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ullamcorper varius urna,
          in ultrices arcu auctor sed. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
          Etiam dolor tellus, posuere at tincidunt at, consequat et mi. Curabitur accumsan aliquet interdum. Nullam lacus velit, varius non dictum in,
          interdum vel tortor. Maecenas eget quam lorem, vel aliquet tellus. Fusce vel diam diam, at vehicula lorem. Nunc consequat dolor vel ante
          accumsan auctor. Aenean euismod, quam sit amet mattis tincidunt, odio massa iaculis lectus, in consectetur quam sapien in lorem. Suspendisse blandi
          t feugiat ipsum vitae gravida. Maecenas et turpis odio. Vivamus nec velit ac purus vestibulum ullamcorper. Aliquam nisi tortor, viverra ut ullamcorper nec, cursus id felis. Sed lorem quam, vehicula in consequat
          id, auctor eget odio. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. ");
      $post->setRawContent("

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ullamcorper varius urna,
          in ultrices arcu auctor sed. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
          Etiam dolor tellus, posuere at tincidunt at, consequat et mi. Curabitur accumsan aliquet interdum. Nullam lacus velit, varius non dictum in,
          interdum vel tortor. Maecenas eget quam lorem, vel aliquet tellus. Fusce vel diam diam, at vehicula lorem. Nunc consequat dolor vel ante
          accumsan auctor. Aenean euismod, quam sit amet mattis tincidunt, odio massa iaculis lectus, in consectetur quam sapien in lorem. Suspendisse blandi
          t feugiat ipsum vitae gravida. Maecenas et turpis odio. Vivamus nec velit ac purus vestibulum ullamcorper. Aliquam nisi tortor, viverra ut ullamcorper nec, cursus id felis. Sed lorem quam, vehicula in consequat
          id, auctor eget odio. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. ");
      $post->setUser($em->merge($this->getReference("User-aldor")));
      $post->setDescription("Krotki wstep. bardzo");
           $post->setDate(new \DateTime);
      $post->setModified(new \DateTime);
      $post->setPublicationDateStart(new \DateTime);
      $post->setStatus("public");
      $post->setCommentStatus("open");
      $post->setUrl($post->getCategorySlug().$post->getDateSlug().$post->getTitleSlug());
        $post->setMedia($media);
      $post2 = new Post();
      $post2->setCategory($em->merge($this->getReference("PostCategory-cubie")));
      $post2->setCommentCount(0);
      $post2->setTitle("Drugi Testowy tytuł dokumentu".$i);
      $post2->setDescription("Krotki wstep. bardzo");
      $post2->setContent("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ullamcorper varius urna,
          in ultrices arcu auctor sed. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
          Etiam dolor tellus, posuere at tincidunt at, consequat et mi. Curabitur accumsan aliquet interdum. Nullam lacus velit, varius non dictum in,
          interdum vel tortor. Maecenas eget quam lorem, vel aliquet tellus. Fusce vel diam diam, at vehicula lorem. Nunc consequat dolor vel ante
          accumsan auctor. Aenean euismod, quam sit amet mattis tincidunt, odio massa iaculis lectus, in consectetur quam sapien in lorem. Suspendisse blandi
          t feugiat ipsum vitae gravida. Maecenas et turpis odio. Vivamus nec velit ac purus vestibulum ullamcorper. Aliquam nisi tortor, viverra ut ullamcorper nec, cur ");
      $post2->setRawContent("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ullamcorper varius urna,
          in ultrices arcu auctor sed. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
          Etiam dolor tellus, posuere at tincidunt at, consequat et mi. Curabitur accumsan aliquet interdum. Nullam lacus velit, varius non dictum in,
          interdum vel tortor. Maecenas eget quam lorem, vel aliquet tellus. Fusce vel diam diam, at vehicula lorem. Nunc consequat dolor vel ante
          accumsan auctor. Aenean euismod, quam sit amet mattis tincidunt, odio massa iaculis lectus, in consectetur quam sapien in lorem. Suspendisse blandi
          t feugiat ipsum vitae gravida. Maecenas et turpis odio. Vivamus nec velit ac purus vestibulum ullamcorper. Aliquam nisi tortor, viverra ut ullamcorper nec, cur ");
      $post2->setUser($em->merge($this->getReference("User-aldor")));
          $post2->setStatus("public");
      $post2->setCommentStatus("close");
       $post2->setDate(new \DateTime);
      $post2->setModified(new \DateTime);
      $post2->setPublicationDateStart(new \DateTime);
      $post2->setUrl($post2->getCategorySlug().$post2->getDateSlug().$post2->getTitleSlug());
        $post2->setMedia($media);
        $post3= new Post();
      $post3->setCategory($em->merge($this->getReference("PostCategory-cubie")));
      $post3->setCommentCount(0);
      $post3->setTitle("trzeci gi Testowy tytuł dokumentu".$i);
      $post3->setContent("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ullamcorper varius urna,
          in ultrices arcu auctor sed. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
          Etiam dolor tellus, posuere at tincidunt at, consequat et mi. Curabitur accumsan aliquet interdum. Nullam lacus velit, varius non dictum in,
          interdum vel tortor. Maecenas eget quam lorem, vel aliquet tellus. Fusce vel diam diam, at vehicula lorem. Nunc consequat dolor vel ante
          accumsan auctor. Aenean euismod, quam sit amet mattis tincidunt, odio massa iaculis lectus, in consectetur quam sapien in lorem. Suspendisse blandi
          t feugiat ipsum vitae gravida. Maecenas et turpis odio. Vivamus nec velit ac purus vestibulum ullamcorper. Aliquam nisi tortor, viverra ut ullamcorper nec, cur");
      $post3->setRawContent("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ullamcorper varius urna,
          in ultrices arcu auctor sed. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
          Etiam dolor tellus, posuere at tincidunt at, consequat et mi. Curabitur accumsan aliquet interdum. Nullam lacus velit, varius non dictum in,
          interdum vel tortor. Maecenas eget quam lorem, vel aliquet tellus. Fusce vel diam diam, at vehicula lorem. Nunc consequat dolor vel ante
          accumsan auctor. Aenean euismod, quam sit amet mattis tincidunt, odio massa iaculis lectus, in consectetur quam sapien in lorem. Suspendisse blandi
          t feugiat ipsum vitae gravida. Maecenas et turpis odio. Vivamus nec velit ac purus vestibulum ullamcorper. Aliquam nisi tortor, viverra ut ullamcorper nec, cur");
      $post3->setDescription("Lorem ipsum jakis teskt ala asf ");
      $post3->setDescription("Lorem ipsum jakis teskt ala asf ");
      $post3->setUser($em->merge($this->getReference("User-aldor")));
      $post3->setUrl("slug");
      $post3->setStatus("public");
      $post3->setCommentStatus("close");
       $post3->setDate(new \DateTime);
      $post3->setModified(new \DateTime);
      $post3->setPublicationDateStart(new \DateTime);
        $post3->setMedia($media);
      $post3->setUrl($post3->getCategorySlug().$post3->getDateSlug().$post3->getTitleSlug());

      $em->persist($post2);
      $em->persist($post);
      $em->persist($post3);
    $em->flush();
      $this->addReference('Post-'.($i-2), $post);
      $this->addReference('Post-'.($i-1), $post2);
      $this->addReference('Post-'.$i, $post3);
  }
  }

  public function getOrder()
  {
    return 3; // the order in which fixtures will be loaded
  }
}

