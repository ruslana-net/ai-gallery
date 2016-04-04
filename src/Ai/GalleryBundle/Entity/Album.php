<?php

namespace Ai\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Album
 *
 * @ORM\Table(name="albums")
 * @ORM\Entity(repositoryClass="Ai\GalleryBundle\Repository\AlbumRepository")
 * @ExclusionPolicy("all")
 */
class Album
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="crdate", type="datetime")
     */
    private $crdate;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="tstamp", type="datetime")
     */
    private $tstamp;

    /**
     * @var integer
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var string
     * @Gedmo\Slug(separator="_", style="default", updatable=true, unique=true, fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Expose
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="descr", type="text", nullable=true)
     * @Expose
     */
    private $descr;

    /**
     * @var string
     *
     * @ORM\Column(name="Icon", type="string", length=255)
     * @Expose
     */
    private $icon;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="album", cascade={"persist", "remove"})
     * @Expose
     */
    private $images;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set crdate
     *
     * @param \DateTime $crdate
     * @return Album
     */
    public function setCrdate($crdate)
    {
        $this->crdate = $crdate;

        return $this;
    }

    /**
     * Get crdate
     *
     * @return \DateTime 
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * Set tstamp
     *
     * @param \DateTime $tstamp
     * @return Album
     */
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;

        return $this;
    }

    /**
     * Get tstamp
     *
     * @return \DateTime 
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Album
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Album
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
     * Set name
     *
     * @param string $name
     * @return Album
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
     * Set descr
     *
     * @param string $descr
     * @return Album
     */
    public function setDescr($descr)
    {
        $this->descr = $descr;

        return $this;
    }

    /**
     * Get descr
     *
     * @return string 
     */
    public function getDescr()
    {
        return $this->descr;
    }

    /**
     * Set icon
     *
     * @param string $icon
     * @return Album
     */
    public function setIcon($icon)
    {
        if($icon == "") return;

        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string 
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Add images
     *
     * @param \Ai\GalleryBundle\Entity\Image $images
     * @return Album
     */
    public function addImage(\Ai\GalleryBundle\Entity\Image $images)
    {
        $this->images[] = $images;

        return $this;
    }

    /**
     * Remove images
     *
     * @param \Ai\GalleryBundle\Entity\Image $images
     */
    public function removeImage(\Ai\GalleryBundle\Entity\Image $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }
}
