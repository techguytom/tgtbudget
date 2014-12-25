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
 * @author  Tom Jenkins <techguytom@nerdery.com>
 */
class AccountController extends Controller
{
    /**
     * Account index page
     *
     * @Route("/account", name="accountView")
     * @Method("GET")
     *
     * @return Response
     */
    public function AccountViewAction()
    {
        $account           = new Account();
        $form              = $this->createForm(
            'accountCreate',
            $account,
            ['action' => $this->generateUrl('accountPost')]
        );
        $user              = $this->get('security.token_storage')
                                  ->getToken()
                                  ->getUser();
        $accountRepository = $this->getDoctrine()
                                  ->getManager()
                                  ->getRepository('AppBundle:Account');
        $accounts          = $accountRepository->findBy(['user' => $user->getID()]);

        return $this->render(
            'AppBundle:Account:account.html.twig',
            array(
                'accountForm' => $form->createView(),
                'accounts'    => $accounts,
            )
        );
    }

    /**
     * Handle post action of Account Create Form
     *
     * @param Request $request
     * @Route("/account", name="accountPost")
     * @Method("POST")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function AccountPostAction(Request $request)
    {
        $account = new Account();
        $form    = $this->createForm('accountCreate', $account);
        $user    = $this->get('security.token_storage')
                        ->getToken()
                        ->getUser();

        $form->handleRequest($request);
        $account->setUser($user);
        if ($form->isValid()) {
            $em = $this->getDoctrine()
                       ->getManager();
            $em->persist($account);
            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Account Saved');
        }
        return $this->redirectToRoute('accountView');

    }

    /**
     * Account Types Settings Page
     *
     * @Route("/settings/account-types", name="accountTypesView")
     * @Method("GET")
     *
     * @return Response
     */
    public function AccountTypesViewAction()
    {
        $accountType = new AccountType();
        $form        = $this->createForm(
            'accountType',
            $accountType,
            ['action' => $this->generateUrl('accountTypesPost')]
        );
        $user        = $this->get('security.token_storage')
                            ->getToken()
                            ->getUser();

        $accountTypeRepository = $this->getDoctrine()
                                      ->getManager()
                                      ->getRepository('AppBundle:AccountType');
        $accountTypes          = $accountTypeRepository->findByUser($user);

        return $this->render(
            'AppBundle:Account:type.html.twig',
            array(
                'form'         => $form->createView(),
                'accountTypes' => $accountTypes,
            )
        );
    }

    /**
     * Handles posting of Account Type form
     *
     * @param Request $request
     * @Route("/settings/account-types", name="accountTypesPost")
     * @Method("POST")
     *
     * @return Response
     * TODO: Error messages not displaying
     */
    public function AccountTypePostAction(Request $request)
    {
        $accountType = new AccountType();
        $form        = $this->createForm('accountType', $accountType);
        $user        = $this->get('security.token_storage')
                            ->getToken()
                            ->getUser();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $accountType->setUser($user);
            $em = $this->getDoctrine()
                       ->getManager();
            $em->persist($accountType);
            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Account Type Added');

        }
        return $this->redirectToRoute('accountTypesView');
    }

}
