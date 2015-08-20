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


use Sonata\FormatterBundle\Extension\BaseProxyExtension;
use Aldor\GalleryBundle\Twig\TokenParser\PhotoCategoryTokenParser;
use Aldor\GalleryBundle\Entity\Photo;

class FormatterGalleryExtension extends BaseProxyExtension
{
    protected $twigExtension;

    /**
     * @param \Twig_Extension $twigExtension
     */
    public function __construct(\Twig_Extension $twigExtension)
    {
        $this->twigExtension = $twigExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedTags()
    {
        return array(
            'photo_category',
            'javascripts',
            'stylesheets',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedMethods()
    {
        return array(
            'Aldor\GalleryBundle\Entity\Photo' => array('getimage', 'gettitle'),
            'Aldor\GalleryBundle\Entity\PhotoCategory' => array('getslug', 'getname', 'getid'),
            'Aldor\GalleryBundle\Entity\Gallery' => array('getslug')

        );
    }

    /* Returns an array of available functions
     *
     * @return array
     */
    public function getAllowedFunctions()
    {
        return array(
            'path_formatter'
            // 'photo_category' => $this->photo_category()
        );
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
     * {@inheritdoc}
     */
    public function getTwigExtension()
    {
        return $this->twigExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'aldor_gallery_formatter_extension';
    }

    /**
     * @param integer $media
     * @param string  $format
     * @param array   $options
     *
     * @return string
     */
    public function photo_category($categoryName)
    {
        return $this->getTwigExtension()->photo_category($categoryName);
    }

    public function path_formatter($routeName, $options = array())
    {
        return '';
        // return $this->getTwigExtension()->getPath($routeName, $options);
    }


}
