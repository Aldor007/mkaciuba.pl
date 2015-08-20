<?php
namespace Aldor\BlogBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Events;

use Doctrine\ORM\Mapping as ORM;
use Aldor\InftechBundle\Utils\Inftech as Inftech;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation as Serializer;
use Application\Sonata\MediaBundle\Entity\Media;
use Aldor\InftechBundle\Entity\Entry;

use Eko\FeedBundle\Item\Writer\ItemInterface;


/**
 * Aldor\BlogBundle\Entity\Post
 */
class Post extends Entry implements ItemInterface
{
    /**
     * @var integer $id
     * @Groups({"list", "details"})
     * @Serializer\Expose
     * @Serializer\Type("integer")
     *
     */
    protected $id;

    /**
     * @var \DateTime $date
     * @Groups({"list", "details"})
     * @Serializer\Type("DateTime")
     */
    protected $date;

    /**
     * @var string $email
     * @Exclude
     */
    protected $email;

    /**
     * @var string $url
     * @Groups({"list", "details"})
     * @Serializer\Type("string")
     */
    protected $url;


    /**
     * @var string $title
     * @Groups({"list", "details"})
     * @Serializer\Type("string")
     */
    protected $title;

    /**
     * @var string $status
     * @Groups({"details"})
     * @Serializer\Type("string")
     */
    protected $status;

    /**
     * @var string $comment_status
     * @Groups({"details"})
     * @Serializer\Type("string")
     */
    protected $comment_status;

    /**
     * @var \DateTime $modified
     * @Groups({"details"})
     * @Serializer\Type("DateTime")
     */
    protected $modified;

    /**
     * @var integer $comment_count
     * @Groups({"list", "details"})
     * @Serializer\Type("integer")
     */
    protected $comment_count;


    /**
     * @var Aldor\BlogBundle\Entity\PostCategory
     * @Groups({"list", "details"})
     * @Serializer\Expose
     * @Serializer\Type("Aldor\BlogBundle\Entity\PostCategory")
     *
     */
    protected $category;

    /**
     * Constructor
     */
    public function __construct()
    {

        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->publicationDateStart = new \DateTime();
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
     * Set date
     *
     * @param \DateTime $date
     * @return Post
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
     * Set email
     *
     * @param string $email
     * @return Post
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function getFeedItemTitle() {

        return $this->getTitle();

    }

    public function getFeedItemDescription() {

        return $this->getDescription();
    }
    public function getFeedItemPubDate() {
        return $this->getDate();

    }

    public function getFeedItemLink() {
        return $this->getUrl();
    }


    /**
     * Set url
     *
     * @param string $url
     * @return Post
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

    /**
     * Set title
     *
     * @param string $title
     * @return Post
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
     * Set status
     *
     * @param string $status
     * @return Post
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set comment_status
     *
     * @param string $commentStatus
     * @return Post
     */
    public function setCommentStatus($commentStatus)
    {
        $this->comment_status = $commentStatus;

        return $this;
    }

    /**
     * Get comment_status
     *
     * @return string
     */
    public function getCommentStatus()
    {
        return $this->comment_status;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
     * @return Post
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
     * @return Post
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
    // ...
    /**
     * @var \DateTime $dateslug
     * @Groups({"list", "details"})
     * @Serializer\Type("string")
     *@Accessor(getter="getDateSlug")
     */
    protected $dateslug;
    // ...
    /**
     * @var string $titleslug
     * @Groups({"list", "details"})
     * @Serializer\Type("string")
     *@Accessor(getter="getTitleSlug")
     */
    protected $titleslug;
     public function getDateSlug()
     {
     return $this->getDate()->format("Y/m");
     }
     public function getCategorySlug()
     {
     return Inftech::slugify($this->getCategory()->getName());
     }
     public function getTitleSlug()
     {
     return Inftech::slugify($this->getTitle());
     }



    /**
     * Set category
     *
     * @param Aldor\BlogBundle\Entity\PostCategory $category
     * @return Post
     */
    public function setCategory(\Aldor\BlogBundle\Entity\PostCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Aldor\BlogBundle\Entity\PostCategory
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * @ORM\PrePersist
     */
    public function preInsert()
    {
        // Add your code hebrev
        

        if (!$this->getPublicationDateStart()) {
            $this->setPublicationDateStart(new \DateTime());
        }


        $this->date = $this->getPublicationDateStart();
        $this->modified = $this->date;

        if (!$this->getContentFormatter()) {
            $this->setContentFormatter('richhtml');
        }
        $this->setUrl($this->getDateSlug().'/'.$this->getTitleSlug());
        $this->setTitleSlug($this->getTitleSlug());
        $this->setCommentCount(0);

    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        // Add your code here
        $this->modified = new \DateTime();
        if (!$this->public) {
            $this->date = $this->getPublicationDateStart();
        }
        $this->setUrl($this->getDateSlug().'/'.$this->getTitleSlug());
        $this->setTitleSlug($this->getTitleSlug());

    }
    /**
     * @var Aldor\InftechBundle\Entity\User
     * @Groups({"list", "details"})
     * @Serializer\Expose
     * @Serializer\Type("Application\Sonata\UserBundle\Entity\User")
     */
    protected $user;


    /**
     * Set user
     *
     * @param Aldor\InftechBundle\Entity\User $user
     * @return Post
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User  $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return Aldor\InftechBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @Groups({"details"})
     * @Serializer\Expose
     * @Serializer\Type("ArrayCollection<Aldor\BlogBundle\Entity\Tag>")
     */
    protected $tags;


    /**
     * Add tags
     *
     * @param Aldor\BlogBundle\Entity\Tag $tags
     * @return Post
     */
    public function addTag(\Aldor\BlogBundle\Entity\Tag $tags)
    {
        $tags->increment();
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param Aldor\BlogBundle\Entity\Tag $tags
     */
    public function removeTag(\Aldor\BlogBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
        $tags->decrement();
    }

    /**
     * Get tags
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }
    /**
     * @var string
     * @Groups({"list"})
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    protected $description;


    /**
     * Set description
     *
     * @param string $description
     * @return Post
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


    



    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     * @Groups({"list", "details"})
     * @Serializer\Type("Application\Sonata\MediaBundle\Entity\Media")
     */
    protected $media;


    /**
     * Set media
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $media
     * @return Post
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
    /**
     * @var \DateTime
     */
    protected $publicationDateStart;


    /**
     * Set publicationDateStart
     *
     * @param \DateTime $publicationDateStart
     * @return Post
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
     * @var string
     */
    protected $contentFormatter;

    /**
     * Set contentFormatter
     *
     * @param string $contentFormatter
     * @return Post
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
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $rawContent;


    /**
     * Set content
     *
     * @param string $content
     * @return Post
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
     * @return Post
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
     * @ORM\PreRemove
     */
    public function preRemove()
    {
        $this->setMedia(null);
    }
    /**
     * @var \DateTime
     */
    protected $publication_date_start;


    /**
     * @var string
     */
    protected $title_slug;


    /**
     * Set title_slug
     *
     * @param string $titleSlug
     * @return Post
     */
    public function setTitleSlug($titleSlug)
    {
        $this->title_slug = $titleSlug;

        return $this;
    }
    /**
     * @var string
     */
    protected $keywords;


    /**
     * Set keywords
     *
     * @param string $keywords
     * @return Post
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

    public function __toString() {
        return 'https://mkaciuba.pl/blog/'.$this->url;
    }

     public function getSeoType()
    {
        return 'post';
    }
}
