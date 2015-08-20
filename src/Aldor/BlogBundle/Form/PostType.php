<?php

namespace Aldor\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date')
            ->add('url')
            ->add('text')
            ->add('title')
            ->add('status')
            ->add('comment_status')
            ->add('modified')
            ->add('comment_count')
            ->add('category')
            ->add('user')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aldor\BlogBundle\Entity\Post'
        ));
    }

    public function getName()
    {
        return 'aldor_inftechbundle_posttype';
    }
}
