<?php
// src/Ens/JobeetBundle/Admin/CategoryAdmin.php

namespace Aldor\BackendBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\FormatterBundle\Formatter\Pool as FormatterPool;
use Aldor\BackendBundle\Admin\BaseAdmin;

class ProjectAdmin extends BaseAdmin
{
// setup the default sort column and order
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'date'
    );
    public $supportsPreviewMode = true;


 /**
     * @var Pool
     */
    protected $formatterPool;
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('keywords')
            ->add('description', 'ckeditor')
            ->add('content','sonata_formatter_type', array(
                    'event_dispatcher' => $formMapper->getFormBuilder()->getEventDispatcher(),
                    'format_field'   => 'contentFormatter',
                    'source_field'   => 'rawContent',
                    // 'ckeditor_toolbar_icons' => array('full'),
                    'source_field_options'      => array(
                                'attr' => array('class' => 'span10', 'rows' => 20)
                            ),
                    'ckeditor_context'     => 'portfolio',
                    'target_field'   => 'content',
                    'listener'       => true,
                ))
            ->add('media', 'sonata_type_model_list', array('required' => false), array(
                    'link_parameters' => array(
                        'context' => 'portfolio',
                        'hide_context' => true
                    )
                ))
            ->add('full_photo_size', null, array('required' => false))
            ->add('public', null, array('required' => false))
            ->add('technologies', 'sonata_type_model',array('expanded' => true, 'by_reference' => true, 'multiple' => true))

        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('url')
            ->add('description')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('media', 'string', array('template' => 'AldorBackendBundle:PostAdmin:thumb.html.twig'))
            ->add('description', 'string', array('template' => 'AldorBackendBundle:PostAdmin:description.html.twig'))
            ->add('technologies')
        ;
    }
    
    protected function _clearReverseProxyCache($param, $object, $router)
    {
        $this->_invalidatePath('/projekty', $param);
        $this->_invalidatePath($router->generate('portfolio_project', array('slug' => $object->getSlug())), $param);
        $tags = $object->getTechnologies();
        foreach ($tags as $tag) {
            $this->_invalidatePath($router->generate('portfolio_technology', array('slug' => $tag->getSlug())), $param);
        }
    }
     /**
     * @param \Sonata\FormatterBundle\Formatter\Pool $formatterPool
     *
     * @return void
     */
    public function setPoolFormatter(FormatterPool $formatterPool)
    {
        $this->formatterPool = $formatterPool;
    }


    /**
     *      * {@inheritdoc}
     *           */
    public function preUpdate($object)
    {
        $object->setContent($this->formatterPool->transform($object->getContentFormatter(), $object->getRawContent()));




        return $object;
    }

    /**
     *      * {@inheritdoc}
     *           */
    public function prePersist($object)
    {
        $object->setContent($this->formatterPool->transform($object->getContentFormatter(), $object->getRawContent()));
        return $object;
    }

    public function postPersist($object)
    {

        $cacheManager = $this->getConfigurationPool()->getContainer()->get('aldor.inftech.cachemanager');
        $posts_allKey = $cacheManager::KEY_PROJECT_ALL;
        $cacheManager->delete($posts_allKey);
        $this->clearReverseProxyCache($object);
        return $object;
    }
    public function postUpdate($object)
    {

        $cacheManager = $this->getConfigurationPool()->getContainer()->get('aldor.inftech.cachemanager');
        $posts_allKey = $cacheManager::KEY_PROJECT_ALL;
        $cacheManager->delete($posts_allKey);
        $this->clearReverseProxyCache($object);
        return $object;
    }
}
