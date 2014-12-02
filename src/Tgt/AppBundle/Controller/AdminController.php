<?php

namespace Tgt\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DefaultController
 * @package Tgt\AppBundle\Controller
 *
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('TgtAppBundle:Admin:index.html.twig');
    }
}
