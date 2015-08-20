<?php

namespace Aldor\BackendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Collection;
use Aldor\GalleryBundle\Form\PhotoCategoryType;

class BatchPhotoCategoryType extends   AbstractType
{
    protected $galleries;

    public function __construct($galleries) {
        $this->galleries = $galleries;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('prefix_name')
            ->add('zip', 'file')
            ->add('gallery', 'entity', array(
                'class' => 'AldorGalleryBundle:Gallery',
                'choices' => $this->galleries))
            ->add('donwnloadable', 'checkbox', array('required' => false));
    }


    public function getName()
    {
        return 'batch_photo_category_zip';
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection(array(
            'zip' => array(
                new NotBlank(array('message' => 'nie moze byc puste')),
            ),
            'name' => array(
                new NotBlank(array('message' => 'Nie moze byc puste.')),
            ),
            'prefix_name' => array(
                new Type(array('type' => 'string', 'message' => 'To ma byc string')),
            ),
            'gallery' => array(
                new NotBlank(array('message' => 'Nie moze byc puste.')),
            ),
            'donwnloadable' => array(
                new NotNull(array('message' => ' nie')),
            )
        ));

        $resolver->setDefaults(array(
            'constraints' => $collectionConstraint
        ));
    }
}
