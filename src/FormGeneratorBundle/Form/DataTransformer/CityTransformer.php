<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 08/06/2015
 * Time: 16:34
 */

namespace FormGeneratorBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CityToObjectTransformer implements DataTransformerInterface {

    private $om;

    public function __construct($om)
    {
        $this->om = $om;
    }

    /**
     * @param mixed $city
     * @return mixed|string
     */
    public function transform($city)
    {
        if(null === $city) {
            return "";
        }

        return $city->getName();


    }
    /**
     * Transforms a string (id) to an object (City).
     *
     * @param  string $id
     * @return City|null
     * @throws TransformationFailedException if object (City) is not found.
     */
    public function reverseTransform($id)
    {
        if(!$id) {
            return null;
        }

        $city = $this->om
            ->getRepository('FormGeneratorBundle:City')
            ->find($id);

        if (null === $city) {
            throw new TransformationFailedException(sprintf(
                'Aucune objet avec l\'id de %s trouvé !',
                $id
            ));
        }

        return $city;
    }
}