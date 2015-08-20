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
class RelatedPostsBlockService  extends BaseBlockService
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
        return 'Related posts';
    }

    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'title'    => 'Powiązane posty',
            'template' => 'AldorBlogBundle:Post:related_posts.html.twig',
            'ttl' => 600,
            'url' => null,
            'tags' => null
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
    }public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        $errorElement
            ->with('settings.title')
                ->assertNotNull(array())
                ->assertNotBlank()
            ->end();
    }


    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {

        $settings = $blockContext->getSettings();

        $posts = $this->em->getRepository('AldorBlogBundle:Post')->getRelatedPosts($settings['tags'], $settings['url']);
        return $this->renderResponse($blockContext->getTemplate(), array(
            'posts'  => $posts,
            'title' => $settings['title'],
        ), $response);
    }


}

