<?php

namespace Aldor\GalleryBundle\Twig\TokenParser;

use Aldor\GalleryBundle\Twig\Node\PhotoCategoryNode;

class PhotoCategoryTokenParser extends \Twig_TokenParser
{
    protected $extensionName;

    /**
     * @param string $extensionName
     */
    public function __construct($extensionName)
    {
        $this->extensionName = $extensionName;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(\Twig_Token $token)
    {
        $categoryName = $this->parser->getExpressionParser()->parseExpression();

        // $this->parser->getStream()->next();


        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);

        return new PhotoCategoryNode($this->extensionName, $categoryName);
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'photo_category';
    }
}
