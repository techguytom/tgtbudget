<?php
/**
 * AccountController.php
 *
 * @package AppBundle\Controller
 * @subpackage
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
    public function indexAccountAction(Request $request)
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
     * Edit an existing Account
     *
     * @Route("/account/{id}", name="edit_account", requirements={"id" = "\d+"})
     * @Method({"GET", "PUT"})
     * @param Account $account
     * @param Request $request
     * @ParamConverter("account", class="AppBundle:Account")
     *
     * @return Response
     */
    public function editAccountAction(Account $account, Request $request)
    {
        $user = $this->get('security.token_storage')
                     ->getToken()
                     ->getUser();

        if ($user->getId() != $account->getUser()->getId()) {
            return $this->redirectToRoute('account');
        }

        $form = $this->createForm(
            'accountCreate',
            $account,
            ['method' => 'PUT', 'action' => $this->generateUrl('edit_account', ['id' => $account->getId()])]
        );
        $em   = $this->getDoctrine()
                     ->getManager();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($account);
            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Account ' . $account->getName() . ' Updated');
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
