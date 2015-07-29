<?php
/**
 * Created by PhpStorm.
 * User: paul
 * Date: 29/07/15
 * Time: 13:45
 */

namespace GeneratorBundle\Service;


use Doctrine\ORM\EntityManager;

class PdfParser
{
    private $em;
    private $yml;
    private $ui;
    private $fields;
    private $attributes;
    private $collection;

    private $customFieldParser;

    public function __construct(EntityManager $em, CustomFieldsParser $customFieldsParser)
    {
        $this->em = $em;
        $this->customFieldParser = $customFieldsParser;
    }

    public function getSheetToHtml($id)
    {
        $this->getData($id);
        $str = $this->setString();
        return "";
    }

    private function getData($id)
    {
        $data = $this->em->getRepository("GeneratorBundle:OpusSheet")->find($id);
        foreach($data->getAttributes() as $attr)
        {
            $this->attributes[$attr->getLabel()] = $attr;
        }
        foreach($data->getCollections() as $coll)
        {
            $arr = ["collection" => $coll, "attributes" => []];
            foreach($arr["collection"]->getAttributes() as $attr) {
                $arr["attributes"][$attr->getLabel()] = $attr;
            }
            if(!isset($this->collection[$coll->getType()]))
                $this->collection[$coll->getType()] = [];
            array_push($this->collection[$coll->getType()],$arr);
        }
        $this->yml = $this->customFieldParser->parseYamlConf("old_meet.yml");
        $this->ui = $this->yml['ui'];
        $this->fields = $this->yml['fields']["attr"];
    }

    private function setString()
    {
        $str = "";
        foreach($this->ui as $tab)
        {
            foreach($tab["content"] as $contentItem)
            {
                foreach($contentItem as $balise => $content)
                {
                    $str.= $this->parseToHtml($balise,$content);
                }
            }
        }
        dump($str);die;
    }

    private function parseToHtml($balise, $content)
    {
        $str = "";
        $arg = [
            "balise" => "",
            "args" => "",
            "content" => ""
        ];

        $balise = strtolower($balise);
        if($balise === "attribute")
        {
            $arg["balise"] = "p";
            $field = null;
            foreach($this->fields as $f)
            {
                if($f["id"] == $content) {
                    $field = $f;
                    break;
                }
            }
            if($field === null)
                return "";
            $arg["content"] = $this->getVal($content,$field);
            if($arg["content"] === "")
                return "";
            dump($arg);
        }
        elseif($balise === "collection")
        {

        }
        else {
            $recursive = ["table","tr"];
            $arg["balise"] = $balise;
            if(in_array($balise,$recursive))
            {
                foreach($content as $b => $c)
                {
                    foreach($c as $k=>$v)
                    {
                       $arg["content"].=$this->parseToHtml($k,$v);
                    }
                }
            }else
            {
                foreach($content as $id => $value)
                {
                    if($id == "text")
                        $arg["content"] = $value;
                    else{
                        $arg["args"] .= $id . '="';
                        if (is_array($value)) {
                            foreach ($value as $k => $val) {
                                $arg["args"] .= $k . ":" . $val;
                                if ($id == "style")
                                    $arg["args"] .= ";";
                            }
                        } else
                            $arg["args"] .= $value;
                        $arg["args"] .= '" ';

                    }
                }
                $str = $this->baliseToHtml($arg);
            }

        }

        return $str;

    }

    private function getVal($id,$field)
    {
        dump($field);
        if(isset($this->attributes[$id]))
        {
            dump("oh");
            if(strtolower($field["type"]) === "genemu_jquerydate" || strtolower($field["type"]) === "date" || strtolower($field["type"]) === "datetime")
            {
                return $this->attributes[$id]->getValueDate()->format("d-m-Y H:i");
            }else{
                dump($id);
                if($this->attributes[$id]->getValue() !== null)
                    return $this->attributes[$id]->getValue();
                elseif($this->attributes[$id]->getValueData() !== null)
                    return $this->attributes[$id]->getValueData();
                elseif($this->attributes[$id]->getValueBase64() !== null)
                    return $this->attributes[$id]->getValueBase64();
                return "";
            }
        }else
            return "";


    }

    private function baliseToHtml($arg)
    {
        return "<".$arg["balise"]." ".$arg["args"]." >".$arg["content"]. "</".$arg["balise"].">";
    }

}