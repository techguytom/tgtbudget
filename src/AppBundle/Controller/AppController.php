<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
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
     * @Method("GET")
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
     * @Method({"GET", "POST"})
     *
     * @return Response
     */
    public function AccountAction(Request $request)
    {
        $account = new Account();
        $form    = $this->createForm('accountCreate', $account);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()
                       ->getManager();
            $em->persist($account);
            $em->flush();
            //$flash->success('Account Saved');
        }

        return $this->render(
            'AppBundle:Account:account.html.twig',
            array(
                'accountForm' => $form->createView(),
            )
        );
    }

    /**
     * Account Types Settings Page
     *
     * @param Request $request
     * @Route("/settings/account-types", name="account_types")
     * @Method({"GET", "POST"})
     *
     * @return Response
     */
    public function AccountTypesAction(Request $request)
    {
        $accountType = new AccountType();
        $form = $this->createForm('accountType', $accountType);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $accountType->setUser($this->get('security.token_storage')->getToken()->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($accountType);
            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Account Type Added');

        }

        return $this->render(
            'AppBundle:Account:type.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
}
