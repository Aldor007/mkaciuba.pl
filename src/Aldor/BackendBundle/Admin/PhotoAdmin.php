<?php
// src/Ens/JobeetBundle/Admin/CategoryAdmin.php

namespace Aldor\BackendBundle\Admin;


use Sonata\MediaBundle\Admin\BaseMediaAdmin;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\MediaBundle\Provider\Pool;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection as RouteCollection;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Aldor\BackendBundle\Admin\BaseAdmin;
use Aldor\InftechBundle\Cache\CacheManager;


class PhotoAdmin extends BaseAdmin
{
    public $supportsPreviewMode = true;


    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'date'
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('categories')
            ->add('image', 'sonata_type_model_list', array('required' => true), array(
                    'link_parameters' => array(
                        'context' => 'gallery',
                    'provider' => 'sonata.media.provider.image',
                    )
                ))
            ->add('sequence')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('categories')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('image', 'string', array('template' => 'AldorBackendBundle:PostAdmin:thumb.html.twig'))
            ->add('categories')
            ->add('sequence')
            ->add('date')
        ;
    }

    protected function configureSideMenu(ItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && in_array($action, array('edit'))) {
            return;
        }
        $admin = $this->isChild() ? $this->getParent() : $this;
        $menu->addChild('Dodaj zipa', array('uri' => $admin->generateUrl('batch_create')));
    }
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('batch_create');
    }
    protected function _clearReverseProxyCache($param, $object, $router)

    {
        $this->_invalidatePath($router->generate('gallery_photocategory', array(
            'galleryslug' => $object->getCategory()->getGallery()->getSlug(),
            'photocategoryslug' => $object->getCategory()->getSlug()
        )), $param);

        $this->_invalidatePath($router->generate('get_photos', array(
            'category_id' => $object->getCategory()->getId()
        )), $param);


        $cacheManager = $this->getConfigurationPool()->getContainer()->get('aldor.inftech.cachemanager');
        // FIXME: zrobic usuluge na x-device-type
        $cacheManager->delete(CacheManager::KEY_PHOTOSCATEGORY_API.$object->getCategory()->getId().'full');
        $cacheManager->delete(CacheManager::KEY_PHOTOSCATEGORY_API.$object->getCategory()->getId().'mobile');
        $cacheManager->delete(CacheManager::KEY_PHOTOSCATEGORY_API.$object->getCategory()->getId().'tablet');
        $cacheManager->delete(CacheManager::KEY_PHOTOS.$object->getCategory()->getId());


    }
    public function postPersist($object) {
        $this->clearReverseProxyCache($object);
    }

    public function postInsert($object) {
        $this->clearReverseProxyCache($object);
    }

    public function postUpdate($object) {
        $this->clearReverseProxyCache($object);
    }
}
