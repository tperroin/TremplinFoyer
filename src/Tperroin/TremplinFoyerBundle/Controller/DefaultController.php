<?php

namespace Tperroin\TremplinFoyerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TperroinTremplinFoyerBundle:Default:index.html.twig');
    }
}
