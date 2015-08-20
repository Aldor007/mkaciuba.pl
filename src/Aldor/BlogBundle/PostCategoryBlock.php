<?php

namespace  Aldor\BlogBundle\Block;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use JMS\Serializer\SerializationContext;

class  PostCategoryBlock {

    protected $m_enityManager;
    protected $m_cacheManager;

    public function __construct($type, $templating, $em, $cacheManager)
    {
        $this->type = $type;
        $this->templating = $templating;
        $this->m_enityManager = $em;
        $this->m_cacheManager = $cacheManager;
    }



    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'title'    => 'Ostatnie posty',
            'max' => 3,
            'template' => 'AldorBlogBundle:Post:recent_posts_from_category.html.twig',
        ));
    }

    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('title', 'text', array('required' => false)),
                array('max', 'number', array('required' => false)),
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
        // merge settings
        $settings = $blockContext->getSettings();
        $max = $settings['max'];
        $cacheKey = 'recentPostfromCategoryAction_'.$category->getSlug().'_'.$max;
        $posts = $this->m_cacheManager->get($cacheKey, 'ArrayCollection<Aldor\BlogBundle\Entity\Post>');
        if (!$posts) {
            $posts = $this->m_enityManager->getRepository('AldorBlogBundle:Post')->getRecentPostsFromCategory($category->getId(), $max);
            $context = SerializationContext::create();
            $context->setGroups(array('list'));
            $context->setSerializeNull(true);
            $this->m_cacheManager->put($cacheKey, $posts, 36000);
        }

        return $this->renderResponse($blockContext->getTemplate(), array(
            'posts'     => $posts,
            'block'     => $blockContext->getBlock(),
            'settings'  => $settings
        ), $response);
    }
}
