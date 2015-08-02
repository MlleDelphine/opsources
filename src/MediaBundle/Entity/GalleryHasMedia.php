<?php

namespace MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseGalleryHasMedia as BaseGalleryHasMedia;
use Doctrine\ORM\Mapping as ORM;

/**
 * This file is part of the Opus project.
 *
 * @ORM\Table("media_gallery_has_media")
 * @ORM\Entity(repositoryClass="MediaBundle\Entity\Repository\GalleryHasMediaRepository")
 */
class GalleryHasMedia extends BaseGalleryHasMedia
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * Get id.
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }
}
