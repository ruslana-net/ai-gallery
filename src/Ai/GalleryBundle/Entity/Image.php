<?php

namespace Ai\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Image
 *
 * @ORM\Table(name="images")
 * @ORM\Entity(repositoryClass="Ai\GalleryBundle\Repository\ImageRepository")
 * @Gedmo\Uploadable
 * @ExclusionPolicy("all") *
 */
class Image
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
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Expose
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     * @Gedmo\UploadableFileName
     * @Expose
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="Album", inversedBy="images")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id")
     */
    private $album;

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
     * @return Image
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
     * @return Image
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
     * @return Image
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
     * Set name
     *
     * @param string $name
     * @return Image
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
     * @return Image
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
     * Set album
     *
     * @param \Ai\GalleryBundle\Entity\Album $album
     * @return Image
     */
    public function setAlbum(\Ai\GalleryBundle\Entity\Album $album = null)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Get album
     *
     * @return \Ai\GalleryBundle\Entity\Album
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * Get album id
     *
     * @return integer
     */
    public function getAlbumId()
    {
        return $this->album ? $this->album->getId() : null;
    }
}
