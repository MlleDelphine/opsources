<?php

namespace MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseMedia as BaseMedia;
use Doctrine\ORM\Mapping as ORM;
use Sonata\MediaBundle\Model\GalleryHasMedia;

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
     * @ORM\OneToOne(targetEntity="GeneratorBundle\Entity\OpusSheetTemplate", mappedBy="confFile")
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
     * @param \Sonata\MediaBundle\Model\GalleryHasMedia $galleryHasMedias
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
     * @param \Sonata\MediaBundle\Model\GalleryHasMedia $galleryHasMedias
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

    /**
     * Overriding method for orphanRemoval suppress media instead of whole directory
     *
     * @param $binaryContent
     */
    public function setBinaryContent($binaryContent)
    {
        if ($this->providerReference) {
            $this->previousProviderReference = $this->providerReference;
        }
        $this->providerReference = null;
        $this->binaryContent = $binaryContent;

        if(!$this->providerReference && $this->previousProviderReference){
            $this->providerReference = $this->previousProviderReference;
        }

    }
}
