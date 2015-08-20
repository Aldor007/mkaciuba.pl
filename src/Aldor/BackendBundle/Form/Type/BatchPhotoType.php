<?php

namespace Aldor\BackendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class BatchPhotoType extends AbstractType
{

    protected $categories;
    public function __construct($categories) {
        $this->categories = $categories;

    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('zip', 'file')
            ->add('name', 'entity', array(
                'class' => 'AldorGalleryBundle:PhotoCategory',
                'choices' => $this->categories
            ));
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
        ));

        $resolver->setDefaults(array(
            'constraints' => $collectionConstraint
        ));
    }

    public function getName()
    {
        return 'batch_photo';
    }
}
