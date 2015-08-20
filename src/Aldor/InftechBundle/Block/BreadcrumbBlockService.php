<?php
namespace Aldor\InftechBundle\Block;

use Sonata\SeoBundle\Block\Breadcrumb\BaseBreadcrumbMenuBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;

class BreadcrumbBlockService extends BaseBreadcrumbMenuBlockService
{
    /**
     ** {@inheritdoc}
     **/
    public function getName()
    {
        return 'aldor.inftech.block.breadcrumb';
    }

    /**
     ** {@inheritdoc}
     **/
    protected function getMenu(BlockContextInterface  $options)
    {

        $menu = $this->getMenu($options);

        $menu->addChild('my_awesome_action');

        return $menu;
    }
}


