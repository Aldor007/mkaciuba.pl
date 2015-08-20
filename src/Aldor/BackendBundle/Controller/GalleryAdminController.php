<?php
// src/Ens/JobeetBundle/Controller/CategoryAdminController.php
namespace Aldor\BackendBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;

class GalleryAdminController extends Controller
{
     // setup the default sort column and order
    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'name'
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
                   ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('slug')
        ;
    }
}
