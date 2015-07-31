<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 04/06/2015
 * Time: 13:54
 */
namespace GeneratorBundle\Service;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use UserBundle\Entity\User;

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
        $finder = new Finder();
        $iterator = $finder->files()->name($name->getProviderReference())->in($this->kernel->getRootDir() .'/../web/uploads/media/default' );

        foreach ($iterator as $file)
        {
            $template = $file->getRealpath();
        }

        $path = $this->kernel->getRootDir() . '/config/BaseFormMeet/old_meet2.yml';
        try {
            if($field && $field == "fields"){
                $attributesParsed = $yaml->parse(file_get_contents($path)); // $template
                $attributes = $attributesParsed[$field]['attr'];
                $allAttributes = array();
                foreach($attributes as $k => $attribute){
                    if($attribute['type'] == "collection"){
                        $allAttributes['collections'][$k] = $attribute;
                    }
                    else{
                        $allAttributes['attr'][$k] = $attribute;
                    }
                }
            }
            elseif($field){
                $attributesParsed = $yaml->parse(file_get_contents($template));
                $allAttributes = $attributesParsed[$field];
            }
            else{
                $allAttributes = $yaml->parse(file_get_contents($template));
            }

        } catch (ParseException $e) {
            throw new ParseException("Unable to parse the YAML string: %s", $e->getMessage());
        }
        return $allAttributes;

    }

    /**
     * Liste tous les fichiers de configuration de formulaire
     * @return array
     */
    public function listAllFiles(){

        $path = $this->kernel->getRootDir() . '/config/BaseFormMeet/';
        $conf = array_diff(scandir($path), array('..', '.'));

        return array_combine($conf, $conf);
    }

}
