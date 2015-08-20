<?php

namespace Aldor\GalleryBundle\Entity;

use Aldor\InftechBundle\Utils\Inftech as Inftech;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation as Serializer;

/**
 * Aldor\GalleryBundle\Entity\PhotoCategory
 */
class PhotoCategory
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
     * @var string $image
     */
    private $image;

    
    private $photos;

    /**
     * @var Aldor\GalleryBundle\Entity\Gallery
     * @Groups({"list", "details"})
     * @Serializer\Type("Aldor\GalleryBundle\Entity\Gallery")
     */
    private $gallery;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return PhotoCategory
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
     * Set image
     *
     * @param string $image
     * @return PhotoCategory
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add photos
     *
     * @param Aldor\GalleryBundle\Entity\Photo $photos
     * @return PhotoCategory
     */
    public function addPhoto(\Aldor\GalleryBundle\Entity\Photo $photos)
    {
        $this->photos[] = $photos;

        return $this;
    }

    /**
     * Remove photos
     *
     * @param Aldor\GalleryBundle\Entity\Photo $photos
     */
    public function removePhoto(\Aldor\GalleryBundle\Entity\Photo $photos)
    {
        $this->photos->removeElement($photos);
    }

    /**
     * Get photos
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }
    public function getRandomPhoto()
    {
        return $this->photos->get(array_rand($this->photos->toArray()));

    }
    /**
     * Set gallery
     *
     * @param Aldor\GalleryBundle\Entity\Gallery $gallery
     * @return PhotoCategory
     */
    public function setGallery(\Aldor\GalleryBundle\Entity\Gallery $gallery = null)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * Get gallery
     *
     * @return Aldor\GalleryBundle\Entity\Gallery
     */
    public function getGallery()
    {
        return $this->gallery;
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
     * @return PhotoCategory
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
    }
    public function __toString() {
        return $this->name;
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
    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     */
    private $zip;


    /**
     * Set zip
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $zip
     * @return PhotoCategory
     */
    public function setZip(\Application\Sonata\MediaBundle\Entity\Media $zip = null)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getZip()
    {
        return $this->zip;
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
     * @return PhotoCategory
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
     * @return PhotoCategory
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

    public function getSeoType() {

        return 'gallery';
    }
}
