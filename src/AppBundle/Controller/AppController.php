<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Account;
use AppBundle\Entity\AccountType;

/**
 * Class AppController
 *
 * @package AppBundle\Controller
 */
class AppController extends Controller
{
    /**
     * Home Page
     *
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('AppBundle::index.html.twig');
    }

    /**
     * Account index page
     *
     * @param Request $request
     * @Route("/account", name="account")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function AccountAction(Request $request)
    {
        /** TODO: need to persist account */
        $account = new Account();
        $form = $this->createForm('accountCreate', $account);

        return $this->render('AppBundle:Account:index.html.twig', array(
            'accountForm' => $form->createView(),
            )
        );
    }
}
