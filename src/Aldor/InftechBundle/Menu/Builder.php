<?php

namespace Aldor\InftechBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Builder
 *
 * @category   MenuBuilder
 * @package    BraincraftedBootstrapBundle
 * @subpackage Menu
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2012 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 * @link       http://bootstrap.braincrafted.com Bootstrap for Symfony2
 */
class Builder extends ContainerAware
{
    /**
     * Builds the navbar menu.
     *
     * @param \Knp\Menu\FactoryInterface $factory The menu factory
     * @param array                      $options The options array
     *
     * @return \Knp\Menu\ItemInterface The root item
     */
    public function mainMenu(FactoryInterface $factory, array $options)
    {


        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');
        $menu->addChild('Home', array('uri' => '/'));
        $menu->addChild('Blog', array('route' => 'blog_home'));
        $menu->addChild('Projekty', array('route' => 'portfolio_home'));
        $menu->addChild('Fotografie', array('route' => 'gallery_show'));
        return $menu;
    }
    public function galleryMenu(FactoryInterface $factory, array $options)
    {

        $em = $this->container->get('doctrine.orm.entity_manager');
        $menu = $this->mainMenu($factory, $options);
        $categories = $em->getRepository('AldorGalleryBundle:PhotoCategory')->findByGallery($options['gallery']);
        if (count($categories) <= 1) {
            return $menu;
        }
        if ($options['gallery']->getSlug() != 'fotografie') {
            $menu->addChild('Galeria '.$options['gallery']->getName(), array('route' => 'gallery_show',
                'routeParameters' => array('slug' =>  $options['gallery']->getSlug())
             ));
        }
        $menu->addChild('Kategorie', array('uri' => '#'))

            ->setAttribute('class', 'dropdown')
            ->setLabelAttribute('class', 'dropdown')
            ->setChildrenAttribute('class','subnav-entries dropdown-menu nav')
            ->setChildrenAttribute('role','menu');

        foreach ($categories as $cat) {
            $menu['Kategorie']->addChild($cat->getName(), array('route' => 'gallery_photocategory',
                'routeParameters' =>
                    array('galleryslug' => $options['gallery']->getSlug(), 'photocategoryslug' => $cat->getSlug())
            ));

        }
        $category = $options['category'];
        $media = $category->getZip();
        if ($media) {
            $provider = $this->container->get($media->getProviderName());
            $menu->addChild('Pobierz Zip', array('uri' => $provider->generatePublicUrl($media, 'reference')));
        }


        return $menu;
    }
}
