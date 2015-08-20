<?php

namespace Aldor\PortfolioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Aldor\InftechBundle\Utils\Inftech as Inftech;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation as Serializer;
/**
 * Aldor\PortfolioBundle\Entity\Technology
 */
class Technology
{
    /**
     * @var integer $id
     * @Groups({"list", "details"})
     * @Serializer\Expose
     * @Serializer\Type("integer")
     */
    private $id;

    /**
     * @var string $name
     * @Groups({"list", "details"})
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $projects;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->projects = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Technology
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
     * Add projects
     *
     * @param Aldor\PortfolioBundle\Entity\Project $projects
     * @return Technology
     */
    public function addProject(\Aldor\PortfolioBundle\Entity\Project $projects)
    {
        $this->projects[] = $projects;

        return $this;
    }

    /**
     * Remove projects
     *
     * @param Aldor\PortfolioBundle\Entity\Project $projects
     */
    public function removeProject(\Aldor\PortfolioBundle\Entity\Project $projects)
    {
        $this->projects->removeElement($projects);
    }

    /**
     * Get projects
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * @var string
     * @Groups({"list", "details"})
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    private $slug;


    /**
     * Set slug
     *
     * @param string $slug
     * @return Technology
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
    public function __toString() {
        return $this->getName();
    }
}
