<?php

namespace Aldor\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Aldor\InftechBundle\Utils\Inftech as Inftech;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation as Serializer;
/**
 * Aldor\BlogBundle\Entity\PostCategory
 */
class PostCategory
{
    /**
     * @var integer $id
     * @Groups({"list", "details"})
     * @Serializer\Type("integer")
     */
    private $id;

    /**
     * @var string $name
     * @Groups({"list", "details"})
     * @Serializer\Type("string")
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @Exclude
     */
    private $posts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return PostCategory
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add posts
     *
     * @param Aldor\BlogBundle\Entity\Post $posts
     * @return PostCategory
     */
    public function addPost(\Aldor\BlogBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;
    
        return $this;
    }

    /**
     * Remove posts
     *
     * @param Aldor\BlogBundle\Entity\Post $posts
     */
    public function removePost(\Aldor\BlogBundle\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }
    /**
     * @var string
     * @Groups({"list", "details"})
     * @Serializer\Type("string")
     */
    private $slug;


    /**
     * Set slug
     *
     * @param string $slug
     * @return PostCategory
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @ORM\PrePersist
     */
    public function setSlugValue()
    {
        // Add your code here
         $this->slug = Inftech::slugify($this->getName());

    }
    public function __toString() {
        return $this->name;
    }
}
