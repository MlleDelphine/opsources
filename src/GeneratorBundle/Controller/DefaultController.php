<?php

namespace GeneratorBundle\Controller;

use GeneratorBundle\Entity\OpusSheet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($codeText)
    {
        $em = $this->getDoctrine()->getManager();

        $SheetType = $em->getRepository("GeneratorBundle:OpusSheetType")->findOneByStrCode($codeText);
        $campaign = $em->getRepository("GeneratorBundle:OpusInfo")->findLastInfoByType($SheetType);

        $uiTab = $this->get('app.customfields_parser')->parseYamlConf($campaign->getConfFileUi());
        $allAttributes = $this->get('app.customfields_parser')->parseYamlConf($campaign->getConfFile(), 'fields');
        $name = $this->get('app.customfields_parser')->parseYamlConf($campaign->getConfFile(), 'name');

        $opusSheet = new OpusSheet();

        $form = $this->get('app.prepopulate_entity')->populateOpusSheet($opusSheet, $allAttributes);

        return $this->render('GeneratorBundle:Default:generator.html.twig', array(
            'entity' => $opusSheet,
            'name' => $name,
            'uiTab' => $uiTab,
            'form'   => $form->createView(),
        ));
    }
}
