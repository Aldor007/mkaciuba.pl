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
class TechnologiesBlockService extends BaseBlockService
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
        'ribbon'   => 'ribbon'
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

        $qb = $this->em->createQueryBuilder()->select('t')->from('AldorPortfolioBundle:Technology','t');
        $entities = $qb->getQuery()->getResult();
        $settings = $blockContext->getSettings();
        return $this->renderResponse($blockContext->getTemplate(), array(
            'tags'  => $entities,
            'title' => $settings['title'],
            'ribbon' => $settings['ribbon']
        ), $response);
    }


}

