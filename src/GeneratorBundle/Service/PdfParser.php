<?php

/**
 * Created by PhpStorm.
 * User: paul
 * Date: 29/07/15
 * Time: 13:45.
 */

namespace GeneratorBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

class PdfParser
{
    private $em;
    private $yml;
    private $ui;
    private $fields;
    private $attributes;
    private $collection;
    private $evaluator;
    private $evaluate;
    private $opusSheet;

    private $container;

    private $customFieldParser;

    public function __construct(EntityManager $em, CustomFieldsParser $customFieldsParser, Container $container)
    {
        $this->em = $em;
        $this->customFieldParser = $customFieldsParser;
        $this->container = $container;
    }

    public function getSheetToHtml($id)
    {
        $this->getData($id);


        return $this->setString();
    }

    private function getData($id)
    {
        $data = $this->em->getRepository('GeneratorBundle:OpusSheet')->find($id);
        $this->opusSheet = $data;
        $this->evaluate = $data->getEvaluate();
        $this->evaluator = $data->getEvaluator();
        foreach ($data->getAttributes() as $attr) {
            $this->attributes[$attr->getLabel()] = $attr;
        }
        foreach ($data->getCollections() as $coll) {
            $arr = ['collection' => $coll, 'attributes' => []];
            foreach ($arr['collection']->getAttributes() as $attr) {
                $arr['attributes'][$attr->getLabel()] = $attr;
            }
            if (!isset($this->collection[$coll->getType()])) {
                $this->collection[$coll->getType()] = [];
            }
            array_push($this->collection[$coll->getType()], $arr);
        }
        $this->yml = $this->customFieldParser->parseYamlConf($data->getOpusTemplate()->getConfFile());
        $this->ui = $this->yml['tabs_ui'];
        $this->fields = $this->yml['fields']['attr'];
    }

    private function setString()
    {
        $str = '';
        $str .= $this->getHeader();
        foreach ($this->ui as $tab) {
            foreach ($tab['content'] as $contentItem) {
                //Ex : tag = table et content = thead, tr, th, h3..
                foreach ($contentItem as $tag => $content) {
                    $str .= $this->parseToHtml($tag, $content);
                }
            }
        }

        return $str;
    }

    private function parseToHtml($tag, $content)
    {
        $str = '';
        $arg = [
            'balise' => '',
            'args' => '',
            'content' => '',
        ];

        $tag = strtolower($tag);
        if ($tag === 'attribute' || $tag === 'evaluator' || $tag == 'evaluate') {
            $arg['balise'] = 'p';
            $field = null;
            foreach ($this->fields as $f) {
                if ($f['id'] == $content) {
                    $field = $f;
                    break;
                }
            }

            if ($field === null && $tag === 'attribute') {
                return '';
            }
            $arg['content'] = $this->getVal($content, $field, $tag);

            $str = $this->baliseToHtml($arg);
        }

        elseif ($tag === 'entity_attribute') {
            $arg['balise'] = 'p';
            $arg['args'] = "class=\"line-color\"";

            $label = $content['label'];
            $getValue = 'get'.ucfirst($content['value']);
            $arg['content'] = $label.'<strong>'.$this->opusSheet->$getValue().'</strong>';

            $str = $this->baliseToHtml($arg);
        }

        elseif ($tag === 'collection') {
            if ($this->collection !== null && isset($this->collection[$content])) {
                $data = [];
                $coll = $this->collection[$content];
                foreach ($this->fields as $f) {
                    if ($f['id'] == $content) {
                        $field = $f;
                        break;
                    }
                }

                //Déterminer les id des collections (chaque child) ???
                foreach ($field['child'] as $f) {
                    if (!isset($data[0])) {
                        dump('zero');
                        $data[0] = [];
                    }
                    array_push($data[0], $f['id']);
                }
                foreach ($coll as $i => $col) {
                    foreach ($col['attributes'] as $id => $attr) {
                        if (!isset($data[$i + 1])) {
                            $data[$i + 1] = [];
                        }
                        foreach ($data[0] as $k => $v) {
                            if ($v === $id) {
                                $data[$i + 1][$k] = $this->getRealValAttr($attr);
                            }
                        }
                    }
                }
                $data[0] = array();
                // dump($data);
                //Déterminer les th des collections (chaque child) ???
                foreach ($field['child'] as $f) {
                    array_push($data[0], $f['conf']['label']);
                }

                $str = $this->arrayToTable($data, $field['conf']['label']);
            }
        } else {
            $recursive = ['table', 'tr', 'th', 'td','thead','tbody'];
            $arg['balise'] = $tag;
            if (in_array($tag, $recursive)) {
                if ($content === null) {
                    $arg['content'] = '';
                } else {
                    foreach ($content as $b => $c) {
                        foreach ($c as $k => $v) {
                            $arg['content'] .= $this->parseToHtml($k, $v);
                        }
                    }
                }
                $str = $this->baliseToHtml($arg);
            } else {
                if (is_array($content)) {
                    foreach ($content as $id => $value) {
                        if ($id == 'text') {
                            $arg['content'] = $value;
                        } else {
                            $arg['args'] .= $id.'="';
                            if (is_array($value)) {
                                foreach ($value as $k => $val) {
                                    $arg['args'] .= $k.':'.$val;
                                    if ($id == 'style') {
                                        $arg['args'] .= ';';
                                    }
                                }
                            } else {
                                $arg['args'] .= $value;
                            }
                            $arg['args'] .= '" ';
                        }
                    }
                } else {
                    $arg['content'] = $content;
                }

                $str = $this->baliseToHtml($arg);
            }
        }

        return $str;
    }

    private function getRealValAttr($attr)
    {
        if ($attr->getValue() !== null) {
            return $attr->getValue();
        } elseif ($attr->getValueData() !== null) {
            return $attr->getValueData();
        } elseif ($attr->getValueBase64() !== null) {
            return $attr->getValueBase64();
        }

        return '&nbsp;';
    }

    private function getVal($id, $field, $balise)
    {
        if (isset($this->attributes[$id])) {
            if (strtolower($field['type']) === 'genemu_jquerydate' || strtolower($field['type']) === 'date' || strtolower($field['type']) === 'datetime') {
                return "<strong>".$this->attributes[$id]->getValueDate()->format('d-m-Y H:i')."</strong>";
            }
            else
            {
                $str = '&nbsp;';
                if (isset($field['conf']) && isset($field['conf']['label'])) {
                    $str = $field['conf']['label'].' ';
                }

                //Si type choice on affiche la valeur correspondante du yml pour label(et non l'index du choix)
                if($field['type'] == "choice") {
                    return $str.'<br><strong>'.$field['conf']['choices'][$this->attributes[$id]->getValue()].'</strong>';
                }

                return $str.'<br><strong>'.$this->getRealValAttr($this->attributes[$id]).'</strong>';
            }
        } else {
            $ex = ['evaluator', 'evaluate'];
            if (in_array($balise, $ex) && $this->$balise !== null) {
                $str = '';
                foreach ($this->fields as $field) {
                    if (isset($field['ref']) && $field['ref'] != null && $field['ref'] === $balise.'.'.$id) {
                        $str .= $field['conf']['label'].' ';
                    }
                }
                $get = 'get'.ucfirst($id);

                return $str."<strong>".$this->$balise->$get()."</strong>";
            } elseif (in_array($balise, $ex)) {
                foreach ($this->fields as $field) {
                    if (isset($field['ref']) && $field['ref'] != null && $field['ref'] === $balise.'.'.$id) {
                        return $field['conf']['label'].' ';
                    }
                }
            }
            if (isset($field['conf']) && isset($field['conf']['label'])) {
                return $field['conf']['label'].' ';
            }

            return '&nbsp;';
        }
    }

    private function baliseToHtml($arg)
    {
        $tag = $arg['balise'];
        $args = $arg['args'];
        $content = $arg['content'];

        $str = '';
        if ($tag === '' || $tag === null) {
            return '';
        }
        elseif($tag == 'table') {
            $str .= "<$tag class=\"table table-striped\"";
        }
        else
        {
            $str .= "<$tag";
        }
        if ($args === '' || $args === null) {
            $str .= '>';
        } else {
            $str .= " $args>";
        }

        $str .= $content;
        $str .= "</$tag>";

        return $str;
    }

    /**
     * On passe $data et $label de la collection
     * @param $arr
     * @param $label
     * @return string
     */
    private function arrayToTable($arr, $label)
    {
        $str = "<p>$label</p><table class=\"table table-striped\"><thead><tr style=\"font-size:15px;\">";
        foreach ($arr[0] as $init) {   // ligne arguments
            $str .= "<th>".ucfirst(str_replace('_', ' ', $init)).'</th>';
        }

        $str .= '</tr></thead>';
        foreach ($arr as $i => $v) {
            if ($i !== 0) {
                $str .= '<tr>';
                for ($j = 0;$j < sizeof($arr[0]);++$j) {
                    if (isset($v[$j])) {
                        $str .= "<td>".$v[$j].'</td>';
                    } else {
                        $str .= "<td>-</td>";
                    }
                }
                $str .= '</tr>';
            }
        }

        return $str.'</table>';
    }

    private function getHeader()
    {
        $str = '';
        $str .= '<h1 class="title" style="font-size:4em; text-align: center">'.$this->yml['name'].'</h1>';
        $str .= '<img src="'.$this->container->get('request')->getScheme().'://'.$this->container->get('request')->getHttpHost().$this->container->get('request')->getBasePath().'/bundles/arianespacetheme/images/logo.png">';

        return $str;
    }
}
