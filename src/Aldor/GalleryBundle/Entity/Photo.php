<?php

namespace Aldor\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation as Serializer;
use Application\Sonata\MediaBundle\Entity\Media;


/**
 * Aldor\GalleryBundle\Entity\Photo
 */
class Photo
{
    /**
     * @var integer $id
     * @Groups({"list", "details", "api"})
     * @Serializer\Expose
     * @Serializer\Type("integer")
     */
    protected $id;

    /**
     * @var string $title
     * @Groups({"list", "details", "api"})
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    protected $title;




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
     * Set title
     *
     * @param string $title
     * @return Photo
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Set category
     *
     * @param Aldor\GalleryBundle\Entity\PhotoCategory $category
     * @return Photo
     */
    public function setCategory(\Aldor\GalleryBundle\Entity\PhotoCategory $category = null)
    {
        $this->addCategory($category);
        return $this;
    }

   

    public function __construct() {

        // $this->category =
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @var \Applications\Sonata\MediaBundle\Entity\Media
     *  @Groups({"api", "details", "list"})
     *  @Serializer\Expose
     *  @Serializer\Type("Application\Sonata\MediaBundle\Entity\Media")
     */
    private $image;


    /**
     * Set image
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $image
     * @return Photo
     */
    public function setImage(\Application\Sonata\MediaBundle\Entity\Media $image = null)
    {
        $this->image = $image;
        $this->image->setProviderName('sonata.media.provider.image');

        return $this;
    }

    /**
     * Get image
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getImage()
    {
        return $this->image;
    }
    /**
     * @var \DateTime
     * @Groups({"list", "details", "api"})
     * @Serializer\Expose
     * @Serializer\Type("DateTime")
     */
    private $date;


    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Photo
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
    /**
     * @ORM\PrePersist
     */
    public function preInsert()
    {
        // Add your code home/aldor/www/strona2/web
        $this->date = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        // // Add your code here FIXME:
        // $this->date = new \DateTime();
    }

    public function __tostring()
    {
        return $this->title;
    }
    /**
     * @var integer
     * @Groups({"list", "details", "api"})
     * @Serializer\Expose
     * @Serializer\Type("integer")
     */
    private $sequence = 0;


    /**
     * Set sequence
     *
     * @param integer $sequence
     * @return Photo
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get sequence
     *
     * @return integer 
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Add category
     *
     * @param \Aldor\GalleryBundle\Entity\PhotoCategory $category
     * @return Photo
     */
    public function addCategory(\Aldor\GalleryBundle\Entity\PhotoCategory $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Aldor\GalleryBundle\Entity\PhotoCategory $category
     */
    public function removeCategory(\Aldor\GalleryBundle\Entity\PhotoCategory $category)
    {
        $this->categories->removeElement($category);
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Groups({"list", "details"})
     * @Serializer\Expose
     * @Serializer\Type("ArrayCollection<Aldor\GalleryBundle\Entity\PhotoCategory>")
     * @Serializer\MaxDepth(1)
     */
    private $categories;


    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Get categories
     *
     * @return \Aldor\GalleryBundle\Entity\PhotoCategory
     */
    public function getCategory()
    {
        return $this->categories[0];
    }
}
