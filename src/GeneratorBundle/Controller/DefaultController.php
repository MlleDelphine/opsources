<?php

namespace GeneratorBundle\Controller;

use GeneratorBundle\Entity\OpusSheet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    //On va passer l'ID d'une fiche pour l'éditer

    public function indexAction($codeText)
    {
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

    public function editAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $opusSheet = $em->getRepository("GeneratorBundle:OpusSheet")->findOneById($id);
        $opusTemplate = $opusSheet->getOpusTemplate();
        $template = $opusTemplate->getConfFile();

        $name = $this->get('app.customfields_parser')->parseYamlConf($template, 'name');
        $uiTab = $this->get('app.customfields_parser')->parseYamlConf($template, 'tabs_ui');
        $allAttributes = $this->get('app.customfields_parser')->parseYamlConf($template, 'fields');

        $opusSheet = new OpusSheet();

        $form = $this->get('app.prepopulate_entity')->populateOpusSheet($opusSheet, $allAttributes);

        return $this->render(
            'GeneratorBundle:Default:generator.html.twig',
            array(
                'entity' => $opusSheet,
                'name' => $name,
                'uiTab' => $uiTab,
                'form' => $form->createView(),
            )
        );
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
