<?php

namespace Aldor\BlogBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

use Aldor\InftechBundle\Utils\Inftech as Inftech;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation as Serializer;

/**
 * Aldor\BlogBundle\Entity\Tag
 */
class Tag
{
    /**
     * @var integer $id
     * @Groups({"list", "details"})
     * @Serializer\Expose
     * @Serializer\Type("integer")
     *
     */
    private $id;

    /**
     * @var string $name
     * @Groups({"list", "details"})
     * @Serializer\Type("string")
     */
    private $name;

    /**
     * @var integer $count
     * @Groups({"list", "details"})
     * @Serializer\Expose
     * @Serializer\Type("integer")
     */
    private $count = 0;



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
     * @return Tag
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

    public function __toString() {

        return $this->name;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return Tag
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        if (!$this->count) {
            $this->count = count($this->posts);
        }
        return $this->count;
    }

    public function increment()
    {
        $this->count++;
    }

    public function decrement() {
        $this->count--;

        if ($this->count < 0) {
            $this->count = 0;
        }
    }

    /**
     *
     *@var ArrayCollection
     *
     *
     * @MaxDepth(0)
     */
    private $posts;


    /**
     * Get posts
     *
     * @return \Aldor\BlogBundle\Entity\Post
     */
    public function getPosts()
    {
        return $this->posts;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add posts
     *
     * @param \Aldor\BlogBundle\Entity\Post $posts
     * @return Tag
     */
    public function addPost(\Aldor\BlogBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \Aldor\BlogBundle\Entity\Post $posts
     */
    public function removePost(\Aldor\BlogBundle\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
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
     * @return Tag
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
    public function prePersist()
    {
        // Add your code here
         $this->slug = Inftech::slugify($this->getName());
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        // Add your code here
         $this->slug = Inftech::slugify($this->getName());
    }
}
