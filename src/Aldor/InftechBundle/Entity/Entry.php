<?php

namespace Aldor\InftechBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entry
 */
class Entry
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @var \DateTime
     */
    protected $publicationDateStart;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $contentFormatter;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $rawContent;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $title_slug;

    /**
     * @var string
     */
    protected $keywords;

    /**
     * @var \DateTime
     */
    protected $modified;

    /**
     * @var integer
     */
    protected $comment_count = 0;

    /**
     * @var boolean
     */
    protected $public  = true;


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
     * Set date
     *
     * @param \DateTime $date
     * @return Entry
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
     * Set publicationDateStart
     *
     * @param \DateTime $publicationDateStart
     * @return Entry
     */
    public function setPublicationDateStart($publicationDateStart)
    {
        $this->publicationDateStart = $publicationDateStart;

        return $this;
    }

    /**
     * Get publicationDateStart
     *
     * @return \DateTime
     */
    public function getPublicationDateStart()
    {
        return $this->publicationDateStart;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Entry
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
     * Set contentFormatter
     *
     * @param string $contentFormatter
     * @return Entry
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
     * @return Entry
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
     * @return Entry
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
     * Set description
     *
     * @param string $description
     * @return Entry
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

    public function getRawDescription() {
        $description = $this->description;
        $description = html_entity_decode($description, ENT_QUOTES, 'utf-8');
        $description = preg_replace('/<.*?>/', '', $description);
        $description = str_replace("\xC2\xA0",' ',$description);
        return $description;
    }
    /**
     * Set title
     *
     * @param string $title
     * @return Entry
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
     * Set title_slug
     *
     * @param string $titleSlug
     * @return Entry
     */
    public function setTitleSlug($titleSlug)
    {
        $this->title_slug = $titleSlug;

        return $this;
    }

    /**
     * Get title_slug
     *
     * @return string
     */
    public function getTitleSlug()
    {
        return $this->title_slug;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     * @return Entry
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
     * Set modified
     *
     * @param \DateTime $modified
     * @return Entry
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
     * Set comment_count
     *
     * @param integer $commentCount
     * @return Entry
     */
    public function setCommentCount($commentCount)
    {
        $this->comment_count = $commentCount;

        return $this;
    }

    /**
     * Get comment_count
     *
     * @return integer
     */
    public function getCommentCount()
    {
        return $this->comment_count;
    }

    /**
     * Set public
     *
     * @param boolean $public
     * @return Entry
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }
    /**
     * @ORM\PrePersist
     */
    public function preInsert()
    {
        // Add your code here
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        // Add your code here
    }

    /**
     * @ORM\PreRemove
     */
    public function preRemove()
    {
        // Add your code here
    }
    /**
     * @var string
     */
    private $full_photo_size = 'big';


    /**
     * Set full_photo_size
     *
     * @param string $fullPhotoSize
     * @return Entry
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

    public function getMedia()
    {
        throw "Not implemented";
    }
    public function getTags()
    {
        throw "Not implemented";
    }
    public function getCategory()
    {
        throw "Not implemented";
    }

}
