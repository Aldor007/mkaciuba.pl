<?php
namespace Aldor\InftechBundle\Block;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BaseBlockService;

/**
 * Class CategoriesBlockService
 * @author Marcin Kaciuba
 */
class TagsBlockService extends BaseBlockService
 {
    protected $em;

    public function __construct($type, $templating, $em)
    {
        $this->type = $type;
        $this->templating = $templating;
        $this->em = $em;
    }
  public function getName()
    {
        return 'Tags';
    }

    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
    $resolver->setDefaults(array(
        'title'    => 'Kategorie',
        'template' => 'AldorInftechBundle:Block:tags.html.twig',
        'routeName' => 'blog_tag',
        'entity' => 'AldorBlogBundle:Tag',
        'ribbon'   => 'ribbon',
        'ttl' => 6000
    ));
    }
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('title', 'text', array('required' => false)),
                array('template', 'text', array('required' => false)),
            )
        ));
    }
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        $errorElement
            ->with('settings.title')
                ->assertNotNull(array())
                ->assertNotBlank()
                ->assertMaxLength(array('limit' => 50))
            ->end();
    }


    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {

        $settings = $blockContext->getSettings();
        $entities = $this->em->getRepository($settings['entity'])->findAll();
        return $this->renderResponse($blockContext->getTemplate(), array(
            'entities' => $entities,
            'title' => $settings['title'],
            'routeName' => $settings['routeName'],
            'ribbon' => $settings['ribbon']
        ), $response);
    }


}

