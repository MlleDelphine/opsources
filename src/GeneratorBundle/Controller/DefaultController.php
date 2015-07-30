<?php

namespace GeneratorBundle\Controller;

use GeneratorBundle\Entity\OpusSheet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction($codeText)
    {

        $em = $this->getDoctrine()->getManager();

        $opusSheet = $em->getRepository("GeneratorBundle:OpusSheetTemplate")->findOneById(4);

        dump($opusSheet->getConfFile());
        die;

        foreach ($opusSheet->getCollections() as $attr) {
            foreach($attr->getAttributes() as $at){
                dump($at);
            }


        }


        die;

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

    public function pdfAction($id)
    {
        $text = $this->get('app.pdfparser')->getSheetToHtml($id);
        /*return $this->render('GeneratorBundle:PDF:view.html.twig', array(
            'html' => $text
        ));*/
        $html = $this->renderView('GeneratorBundle:PDF:view.html.twig', array(
            'html' => $text
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="file.pdf"'
            )
        );
    }
}
