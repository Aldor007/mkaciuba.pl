<?php
// src/Ens/JobeetBundle/Admin/CategoryAdmin.php

namespace Aldor\BackendBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\FormatterBundle\Formatter\Pool as FormatterPool;

use Guzzle\Http\Exception\ClientErrorResponseException;

class PostAdmin extends BaseAdmin
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
            ->add('title')
            ->add('keywords')
            ->add('description', 'ckeditor')
            ->add('category', null,  array('required' => true) )
            ->add('media', 'sonata_type_model_list', array('required' => false), array(
                    'link_parameters' => array(
                        'context' => 'blog',
                        'hide_context' => true
                    )
                ))
            ->add('full_photo_size', null, array('required' => false))
            ->add('public', null, array('required' => false))
            ->add('publicationDateStart', 'sonata_type_datetime_picker', array('dp_side_by_side' => true, 'dp_use_current' => true))
            ->add('tags', 'sonata_type_model',array('expanded' => true, 'by_reference' => true, 'multiple' => true))
            // ->add('contentFormatter','choi')
            // ->add('rawContent','ckeditor')
            ->add('content','sonata_formatter_type', array(
                    'event_dispatcher' => $formMapper->getFormBuilder()->getEventDispatcher(),
                    'format_field'   => 'contentFormatter',
                    'source_field'   => 'rawContent',
                    // 'ckeditor_toolbar_icons' => array('full'),
                    'source_field_options'      => array(
                                'attr' => array('class' => 'span10', 'rows' => 20)
                            ),
                    'ckeditor_context'     => 'blog',
                    'target_field'   => 'content',
                    'listener'       => true,
                    'required'    => false
                ))

        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('media')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('description', 'string', array('template' => 'AldorBackendBundle:PostAdmin:description.html.twig'))
            ->add('media', 'string', array('template' => 'AldorBackendBundle:PostAdmin:thumb.html.twig'))
              ->add('date')
              ->add('publicationDateStart')
              ->add('category')
              ->add('tags')
        ;
    }
    protected function _clearReverseProxyCache($param, $object, $router)
    {
        $postUrl = explode('/', $object->getUrl());

        $this->_invalidatePath('/blog', $param);
        $this->_invalidatePath($router->generate('blog_post', array('year' => $postUrl[0], 'month' => $postUrl[1], 'title' => $postUrl[2] )), $param);
        $this->_invalidatePath($router->generate('blog_category', array('slug' => $object->getCategory()->getSlug())), $param);
        $tags = $object->getTags();
        foreach ($tags as $tag) {
            $this->_invalidatePath($router->generate('blog_tag', array('slug' => $tag->getSlug())), $param);

        }


    }

/**
 *      * {@inheritdoc}
 *           */
    public function postPersist($object)
    {
        if ($object->getPublicationDateStart() > (new \DateTime()) || $object->getPublic() == false) {
            return;
        }


        $cacheManager = $this->getConfigurationPool()->getContainer()->get('aldor.inftech.cachemanager');
        $posts_allKey = $cacheManager::KEY_POSTS_ALL;
        $post_categoryKey = $cacheManager::KEY_POSTS_CATEGORY.$object->getCategory()->getSlug();

        $posts_all = $cacheManager->get($posts_allKey, 'ArrayCollection<Aldor\BlogBundle\Entity\Post>', 'json');
        if ($posts_all) {
           array_unshift($posts_all, $object);
        }
        $post_category = $cacheManager->get($post_categoryKey, 'ArrayCollection<Aldor\BlogBundle\Entity\Post>', 'json');
        if ($post_category) {
            array_unshift($post_category, $object);
        }
        $cacheManager->delete($posts_allKey);
        $cacheManager->delete($post_categoryKey);
        $cacheManager->delete($cacheManager::KEY_POST.$object->getUrl());
        $cacheManager->put($post_categoryKey, $post_category, 600,'list');
        $cacheManager->put($posts_allKey, $posts_all, 600, 'list');

        $this->clearReverseProxyCache($object);

        return $object;

    }
    public function postUpdate($object)
    {

        if ($object->getPublic() == false) {
            return;
        }
        $cacheManager = $this->getConfigurationPool()->getContainer()->get('aldor.inftech.cachemanager');
        $posts_allKey = $cacheManager::KEY_POSTS_ALL;
        $post_categoryKey = $cacheManager::KEY_POSTS_CATEGORY.$object->getCategory()->getSlug();

        if ($object->getPublicationDateStart() < (new \DateTime())) {
            $cacheManager->delete($posts_allKey);
            $cacheManager->delete($post_categoryKey);
        }
        $cacheManager->delete($cacheManager::KEY_POST.$object->getUrl());
        $this->clearReverseProxyCache($object);
        return $object;

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
        $user = $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();
        $object->setUser($user);
        if ($object->getRawContent()) {
            $object->setContent($this->formatterPool->transform($object->getContentFormatter(), $object->getRawContent()));
        }



        return $object;
    }

    /**
     *      * {@inheritdoc}
     *           */
    public function prePersist($object)
    {
        $user = $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();
        $object->setUser($user);
        if ($object->getRawContent()) {
            $object->setContent($this->formatterPool->transform($object->getContentFormatter(), $object->getRawContent()));
        }
        return $object;
    }

    public function getBatchActions()
    {
    // retrieve the default (currently only the delete action) actions
        $actions = parent::getBatchActions();
        // check user permissions
        if($this->hasRoute('edit') && $this->isGranted('EDIT') && $this->hasRoute('create') && $this->isGranted('CREATE'))  {
            // define calculate action
            $actions['republication']= array ('label' => 'Republication', 'ask_confirmation'  => true);
        }
        return $actions;
    }

}
