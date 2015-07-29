<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 04/06/2015
 * Time: 13:54
 */
namespace FormGeneratorBundle\Service;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class CustomFieldsParser
{

    /**
     * @var KernelInterface
     */

    private $kernel;

    public function __construct(KernelInterface $kernel) {
        $this->kernel = $kernel;
    }

    /**
     * Parse les configurations de champs dynamiques pour un formulaire.
     * @return mixed
     */
    public function parseYamlConf($name, $field = null){

        $yaml = new Parser();
        $path = $this->kernel->getRootDir() . '/config/BaseFormMeet/'.$name.'.yml';
        try {
            if($field){
                $attributesParsed = $yaml->parse(file_get_contents($path));
                $attributes = $attributesParsed[$field];
            }
            else{
                $attributes = $yaml->parse(file_get_contents($path));
            }

        } catch (ParseException $e) {
            throw new ParseException("Unable to parse the YAML string: %s", $e->getMessage());
        }
        return $attributes;
    }



}
