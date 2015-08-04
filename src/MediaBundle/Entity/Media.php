<?php

namespace MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseMedia as BaseMedia;
use Doctrine\ORM\Mapping as ORM;

/**
 * Media.
 *
 * @ORM\Table("media_media")
 * @ORM\Entity(repositoryClass="MediaBundle\Entity\MediaRepository")
 */
class Media extends BaseMedia
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     *  @var string
     */
    private $identification_name;

    /**
     * @ORM\OneToOne(targetEntity="GeneratorBundle\Entity\OpusSheetTemplate", mappedBy="confFile", cascade={"persist"})
     */
    private $template;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set identification_name.
     *
     * @param string $identificationName
     *
     * @return Media
     */
    public function setIdentificationName($identificationName)
    {
        $this->identification_name = $identificationName;

        return $this;
    }

    /**
     * Get identification_name.
     *
     * @return string
     */
    public function getIdentificationName()
    {
        return $this->identification_name;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->galleryHasMedias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add galleryHasMedias.
     *
     * @param \Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedias
     *
     * @return Media
     */
    public function addGalleryHasMedia(GalleryHasMedia $galleryHasMedias)
    {
        $this->galleryHasMedias[] = $galleryHasMedias;

        return $this;
    }

    /**
     * Remove galleryHasMedias.
     *
     * @param \Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedias
     */
    public function removeGalleryHasMedia(GalleryHasMedia $galleryHasMedias)
    {
        $this->galleryHasMedias->removeElement($galleryHasMedias);
    }

    /**
     * Set template.
     *
     * @param \GeneratorBundle\Entity\OpusSheetTemplate $template
     *
     * @return Media
     */
    public function setTemplate(\GeneratorBundle\Entity\OpusSheetTemplate $template = null)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template.
     *
     * @return \GeneratorBundle\Entity\OpusSheetTemplate
     */
    public function getTemplate()
    {
        return $this->template;
    }
}
