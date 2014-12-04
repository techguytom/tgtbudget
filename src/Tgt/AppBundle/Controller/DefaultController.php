<?php

namespace Tgt\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DefaultController
 * @package Tgt\AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @param $name
     * @Route(“admin/hello”)
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($name)
    {
        return $this->render('TgtAppBundle:Default:index.html.twig', array('name' => $name));
    }
}
