<?php

namespace Aldor\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Aldor\InftechBundle\Utils\Inftech as Inftech;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation as Serializer;
/**
 * Aldor\GalleryBundle\Entity\Gallery
 */
class Gallery
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
     */
    private $categories;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Gallery
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
     * Add categories
     *
     * @param Aldor\GalleryBundle\Entity\PhotoCategory $categories
     * @return Gallery
     */
    public function addCategorie(\Aldor\GalleryBundle\Entity\PhotoCategory $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param Aldor\GalleryBundle\Entity\PhotoCategory $categories
     */
    public function removeCategorie(\Aldor\GalleryBundle\Entity\PhotoCategory $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
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
     * @return Gallery
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
     * Add categories
     *
     * @param \Aldor\GalleryBundle\Entity\PhotoCategory $categories
     * @return Gallery
     */
    public function addCategory(\Aldor\GalleryBundle\Entity\PhotoCategory $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Aldor\GalleryBundle\Entity\PhotoCategory $categories
     */
    public function removeCategory(\Aldor\GalleryBundle\Entity\PhotoCategory $categories)
    {
        $this->categories->removeElement($categories);
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

    public function __toString() {
        return $this->getName();
    }
    /**
     * @var string
     * @Groups({"list", "details"})
     * @Serializer\Type("string")
     */
    private $keywords;

    /**
     * @var string
     * @Groups({"list", "details"})
     * @Serializer\Type("string")
     */
    private $description;


    /**
     * Set keywords
     *
     * @param string $keywords
     * @return Gallery
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get keywords
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Gallery
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
