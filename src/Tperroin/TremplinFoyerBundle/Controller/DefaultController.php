<?php

namespace Tperroin\TremplinFoyerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TperroinTremplinFoyerBundle:Partenaire')->getActivePartenaires();

        return $this->render('TperroinTremplinFoyerBundle:Default:index.html.twig', array('entities' => $entities));
    }
}
