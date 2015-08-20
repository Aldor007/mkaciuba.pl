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
class PostArchiveBlockService  extends BaseBlockService
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
        return 'Recent Posts';
    }

    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'title'    => 'Ostatnie Posty',
            'template' => 'AldorInftechBundle:Block:recent_posts.html.twig',
            'max' => 4,
            'ttl' => 600,
            'category' => null
        ));
    }
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('title', 'text', array('required' => false)),
                array('template', 'text', array('required' => false)),
                array('max', 'number', array('required' => false)),
            )
        ));
    }public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
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
        if ($settings['category']) {
            $posts = $this->em->getRepository('AldorBlogBundle:Post')->getRecentPostsFromCategory($settings['category'], $settings['max']);
        } else {
            $posts = $this->em->getRepository('AldorBlogBundle:Post')->getRecentPosts($settings['max']);
        }

        return $this->renderResponse($blockContext->getTemplate(), array(
            'posts'  => $posts,
            'title' => $settings['title'],
            'category' => $settings['category']
        ), $response);
    }


}

