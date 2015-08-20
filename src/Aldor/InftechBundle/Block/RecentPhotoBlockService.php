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
class RecentPhotoBlockService  extends BaseBlockService
{
    protected $em;

    public function __construct($type, $templating, $em, $deviceDetector)
    {
        $this->type = $type;
        $this->templating = $templating;
        $this->em = $em;
        $this->deviceDetector = $deviceDetector;
    }


  public function getName()
    {
        return 'Recent Photo';
    }

    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'title'    => 'Ostatnie zdjęcia',
            'template' => 'AldorInftechBundle:Block:recent_photo.html.twig',
            'max' => 4,
            'ttl' => 600,
            'gallery' => 'fotografie',
            'active' => false,
            'extra_cache_key' => 'full'
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
        $gallery = $this->em->getRepository('AldorGalleryBundle:Gallery')->findOneBySlug($settings['gallery']);
        $photos = array();
        if ($gallery) {
            $categories = $gallery->getCategories();
            $photos = $this->em->getRepository('AldorGalleryBundle:Photo')->getRecentFromGallery($categories, $gallery->getId(), $settings['max']);

        }

        $imageSize = 'medium'; 
        if ($this->deviceDetector->isMobile()) {
            $imageSize = 'big200';
            $blockContext->setSetting('extra_cache_key', 'mobile');
        }
        return $this->renderResponse($blockContext->getTemplate(), array(
            'entities'  => $photos,
            'title' => $settings['title'].' z galerii '.$settings['gallery'],
            'active' => $settings['active'],
            'gallery' => $gallery,
            'imageSize' => $imageSize
        ), $response);
    }


}

