<?php

/**
 * This file is part of the Opus project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseGallery as BaseGallery;
use Doctrine\ORM\Mapping as ORM;

/**
 * This file is part of the Opus project.
 *
 * @ORM\Table("media_gallery")
 * @ORM\Entity(repositoryClass="MediaBundle\Entity\Repository\GalleryRepository")
 */
class Gallery extends BaseGallery
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
