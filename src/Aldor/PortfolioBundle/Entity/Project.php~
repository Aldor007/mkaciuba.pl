<?php

namespace Aldor\PortfolioBundle\Entity;

use Aldor\InftechBundle\Utils\Inftech as Inftech;
use Doctrine\ORM\Mapping as ORM;
use Aldor\InftechBundle\Entity\Entry;

use Eko\FeedBundle\Item\Writer\ItemInterface;

/**
 * Aldor\PortfolioBundle\Entity\Project
 */
class Project extends Entry  implements ItemInterface
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $description
     */
    protected $description;

    /**
     * @var string $url

     */
    protected $url;


    /**
     * @var Aldor\PortfolioBundle\Entity\Technology
     */
    protected $technology;


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
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->slug = Inftech::slugify($name);

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
     * Get name
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->name;
    }

    public function getTags()
    {
        return $this->technologies;
    }


    public function getCategory() {

        return 'Portfolio';
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Project
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

    public function getShortDescription()
    {
        $ile = 100;
        $licz = strlen($this->description);
        if ($licz>=$ile)  {
            $tnij = substr($this->description,0,$ile);
            $txt = $tnij."...";
        }
        else  {
            $txt = $tresc;
        }
        return $txt;
    }



    /**
     * Set url
     *
     * @param string $url
     * @return Project
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }


    /**
     * Set technology
     *
     * @param Aldor\PortfolioBundle\Entity\Technology $technology
     * @return Project
     */
    public function setTechnology(\Aldor\PortfolioBundle\Entity\Technology $technology = null)
    {
        $this->technology = $technology;

        return $this;
    }

    /**
     * Get technology
     *
     * @return Aldor\PortfolioBundle\Entity\Technology
     */
    public function getTechnology()
    {
        return $this->technology;
    }

     // ...

 public function getTechnologySlug()
 {
 return $this->getTechnology->getSlug();
 }
 public function getNameSlug()
 {
 return Portfolio::slugify($this->getName());
 }
    /**
     * @var \DateTime
     */
    protected $date;


    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Project
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
     * @var string
     */
    protected $slug;


    /**
     * Set slug
     *
     * @param string $slug
     * @return Project
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return \sring
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
         $this->date = new \DateTime();
         $this->modified = $this->date;
         $this->url = $this->getName();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        // Add your code here
         $this->slug = Inftech::slugify($this->getName());
         $this->modified = new \DateTime();
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $technologies;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->technologies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add technologies
     *
     * @param \Aldor\PortfolioBundle\Entity\Technology $technologies
     * @return Project
     */
    public function addTechnology(\Aldor\PortfolioBundle\Entity\Technology $technologies)
    {
        $this->technologies[] = $technologies;

        return $this;
    }

    /**
     * Remove technologies
     *
     * @param \Aldor\PortfolioBundle\Entity\Technology $technologies
     */
    public function removeTechnology(\Aldor\PortfolioBundle\Entity\Technology $technologies)
    {
        $this->technologies->removeElement($technologies);
    }

    /**
     * Get technologies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTechnologies()
    {
        return $this->technologies;
    }
    /**
     * @var string
     */
    protected $text;


    /**
     * Set text
     *
     * @param string $text
     * @return Project
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    public function __toString() {
        return $this->getName();
    }
    /**
     * @var string
     */
    protected $contentFormatter = 'richhtml';

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $rawContent;

    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     */
    protected $media;


    /**
     * Set contentFormatter
     *
     * @param string $contentFormatter
     * @return Project
     */
    public function setContentFormatter($contentFormatter)
    {
        $this->contentFormatter = $contentFormatter;

        return $this;
    }

    /**
     * Get contentFormatter
     *
     * @return string
     */
    public function getContentFormatter()
    {
        return $this->contentFormatter;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Project
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set rawContent
     *
     * @param string $rawContent
     * @return Project
     */
    public function setRawContent($rawContent)
    {
        $this->rawContent = $rawContent;

        return $this;
    }

    /**
     * Get rawContent
     *
     * @return string
     */
    public function getRawContent()
    {
        return $this->rawContent;
    }

    /**
     * Set media
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $media
     * @return Project
     */
    public function setMedia(\Application\Sonata\MediaBundle\Entity\Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    public function getFeedItemTitle() {

        return $this->getName();

    }

    public function getFeedItemDescription() {

        return $this->getDescription();
    }
    public function getFeedItemPubDate() {
        return $this->getDate();

    }

    public function getFeedItemLink() {
        return $this->getSlug();
    }


    /**
     * @var string
     */
    protected $keywords;


    /**
     * Set keywords
     *
     * @param string $keywords
     * @return Project
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
     * @var \DateTime
     */
    protected $modified;


    /**
     * Set modified
     *
     * @param \DateTime $modified
     * @return Project
     */
    public function setModified($modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified
     *
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }
    /**
     * @var boolean
     */
    protected $enabled_photo = true;


    /**
     * Set enabled_photo
     *
     * @param boolean $enabledPhoto
     * @return Project
     */
    public function setEnabledPhoto($enabledPhoto)
    {
        $this->enabled_photo = $enabledPhoto;

        return $this;
    }

    /**
     * Get enabled_photo
     *
     * @return boolean
     */
    public function getEnabledPhoto()
    {
        return $this->enabled_photo;
    }
    /**
     * @var string
     */
    protected $full_photo_size = 'big';


    /**
     * Set full_photo_size
     *
     * @param string $fullPhotoSize
     * @return Project
     */
    public function setFullPhotoSize($fullPhotoSize)
    {
        $this->full_photo_size = $fullPhotoSize;

        return $this;
    }

    /**
     * Get full_photo_size
     *
     * @return string
     */
    public function getFullPhotoSize()
    {
        return $this->full_photo_size;
    }
     public function getSeoType()
    {
        return 'post';
    }
}
