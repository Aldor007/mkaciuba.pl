<?php
// src/Ens/JobeetBundle/Admin/CategoryAdmin.php

namespace Aldor\BackendBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection as RouteCollection;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;

class PhotoCategoryAdmin extends Admin
{
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'id'
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('keywords')
            ->add('description')
            ->add('image', 'sonata_type_model_list', array('required' => false), array(
                    'link_parameters' => array(
                        'context' => 'gallery',
                        'hide_context' => true
                    )
                ))
            ->add('gallery')
             ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('slug')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('slug')
            ->add('gallery')
            ->add('image', 'string', array('template' => 'AldorBackendBundle:PostAdmin:thumb.html.twig'))
            ->add('zip')
        ;
    }
    protected function configureSideMenu(ItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && in_array($action, array('edit'))) {
            return;
        }
        $admin = $this->isChild() ? $this->getParent() : $this;
        $menu->addChild('Dodaj zipa', array('uri' => $admin->generateUrl('create_from_zip')));
    }
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('create_from_zip');
    }
    protected function _clearReverseProxyCache($fosCacheManager, $param, $object, $router)
    {
        $this->_invalidatePath($router->generate('gallery_show', array('slug' => $object->getGallery()->getSlug())), $param);

    }
    public function postPersist($object) {
        $this->clearReverseProxyCache($object);
    }

    public function postInsert($object) {
        $this->clearReverseProxyCache($object);
    }
}
