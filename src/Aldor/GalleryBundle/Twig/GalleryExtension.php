<?php
/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aldor\GalleryBundle\Twig;


use Sonata\FormatterBundle\Extension\BaseExtension;
use Aldor\GalleryBundle\Twig\TokenParser\PhotoCategoryTokenParser;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class GalleryExtension extends \Twig_Extension
{

    /**
     *
     * @var EntityManager
     */
    protected $em;

    protected $environment;

    protected $resources = array();

    protected $pathFunction = null;


    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;

    }


    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return array(
            new  PhotoCategoryTokenParser($this->getName()),
        );
    }

    /**
     * Returns an array of available filters
     *
     * @return array
     */
    public function getAllowedFilters()
    {
        return array();
    }
    /**
     * Returns an array of available tags
     *
     * @return array
     */
    public function getAllowedTags()
    {
        return array(
            'photo_category',
            '__tostring',

        );
    }
    /**
     * Returns an array of available functions
     *
     * @return array
     */
    public function getAllowedFunctions()
    {
        return array(
            // 'path_formatter'
            // 'photo_category' => $this->photo_category()
        );
    }
    public function getFunctions()
    {
        return array(
            'path_formatter' =>new \Twig_Function_Method($this, 'getPath')
        );
    }
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'aldor_gallery_extension';
    }


    public function photo_category($categoryName) {
        $category = $this->em->getRepository('AldorGalleryBundle:PhotoCategory')->findOneByName($categoryName);
        if (!$category) {
            return '';
        }
        $photos = $category->getPhotos();
        $gallery = $category->getGallery();
        return $this->render('AldorGalleryBundle:PhotoCategory:blog-embed.html.twig', array(
            'gallery' => $gallery,
            'photos' => $photos,
            'category' => $category

        ));


    }

    /**
     * @param string $template
     * @param array  $parameters
     *
     * @return mixed
     */
    public function render($template, array $parameters = array())
    {
        if (!isset($this->resources[$template])) {
            $this->resources[$template] = $this->environment->loadTemplate($template);
        }

        return $this->resources[$template]->render($parameters);
    }

    public function getPath($routeName, $options = array()) {
        if (!$this->pathFunction) {
            $this->pathFunction = $this->environment->getFunction('path')->getCallable();
        }

        return  call_user_func($this->pathFunction, $routeName, $options);
    }

}
