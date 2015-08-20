<?php
// src/Ens/JobeetBundle/Admin/CategoryAdmin.php

namespace Aldor\BackendBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class MenuAdmin extends Admin
{
// setup the default sort column and order
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'name'
    );
    public $supportsPreviewMode = true;


    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('label')
            ->add('uri')
            ->add('route')
            ->add('routeParameters')
            ->add('menu')
            ->add('parent')
            ->add('children', 'sonata_type_model',array('expanded' => true, 'by_reference' => true, 'multiple' => true))
            ->add('enabled')
            ->add('display')
            ->add('displayChildren')


        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('label')
            ->add('menu')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('label')
            ->add('menu')
        ;
    }
}
