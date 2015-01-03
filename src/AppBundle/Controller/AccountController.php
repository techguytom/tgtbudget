<?php
/**
 * AccountController.php
 *
 * @package AppBundle\Controller
 * @subpackage
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Account;
use AppBundle\Entity\AccountType;

/**
 * Handles views and forms for account related pages
 *
 * @package AppBundle\Controller
 * @subpackage
 * @author  Tom Jenkins <tom@techguytom.com>
 */
class AccountController extends Controller
{
    /**
     * Account index page
     *
     * @param Request $request
     * @Route("/account", name="account")
     * @Method({"GET", "POST"})
     *
     * @return Response
     */
    public function accountAction(Request $request)
    {
        $account = new Account();
        $form    = $this->createForm('accountCreate', $account);
        $user    = $this->get('security.token_storage')
                        ->getToken()
                        ->getUser();
        $em      = $this->getDoctrine()
                        ->getManager();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $account->setUser($user);
            $em->persist($account);
            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Account Saved');
            $account = new Account();
            $form    = $this->createForm('accountCreate', $account);
        }

        $accounts = $em->getRepository('AppBundle:Account')
                       ->findBy(['user' => $user->getID()]);

        return $this->render(
            'AppBundle:Account:account.html.twig',
            array(
                'accountForm' => $form->createView(),
                'accounts'    => $accounts,
            )
        );
    }

    /**
     * Account Types Settings Page
     *
     * @param Request $request
     * @Route("/settings/account-types", name="accountTypes")
     * @Method({"GET", "POST"})
     *
     * @return Response
     */
    public function accountTypesAction(Request $request)
    {
        $accountType = new AccountType();
        $form        = $this->createForm('accountType', $accountType);
        $user        = $this->get('security.token_storage')
                            ->getToken()
                            ->getUser();
        $em          = $this->getDoctrine()
                            ->getManager();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $accountType->setUser($user);
            $em->persist($accountType);
            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Account Type Added');
            $accountType = new AccountType();
            $form        = $this->createForm('accountType', $accountType);
        }

        $accountTypes = $em->getRepository('AppBundle:AccountType')
                           ->findByUser($user);

        return $this->render(
            'AppBundle:Account:type.html.twig',
            array(
                'form'         => $form->createView(),
                'accountTypes' => $accountTypes,
            )
        );
    }
}
