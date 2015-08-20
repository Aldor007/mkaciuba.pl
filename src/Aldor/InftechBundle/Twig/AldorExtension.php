<?php
namespace Aldor\InftechBundle\Twig;

class AldorExtension extends \Twig_Extension
{
    protected $container;

    public function __construct($container = null)
    {
        $this->container = $container;
    }
    public function getName()
    {
        return 'aldor_media_extension';
    }

    public function getMediaPublicUrl($media, $format)
    {
        $provider = $this->container->get($media->getProviderName());
        $format = $provider->getFormatName($media, $format);

        return $provider->generatePublicUrl($media, $format);
    }
    public function getRelativeUrl($media, $format)
    {
        $publicUrl = $this->getMediaPublicUrl($media, $format);
        return preg_replace("/[a-z:]*\/\/.*?\//", "/", $publicUrl);
    }
     public function getFunctions()
    {
        return array(
            'media_public_url' => new \Twig_Function_Method($this, 'getMediaPublicUrl'),
            'media_relative_url' => new \Twig_Function_Method($this, 'getRelativeUrl')
        );
    }
}
