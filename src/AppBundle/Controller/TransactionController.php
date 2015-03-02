<?php
/**
 * TransactionController.php
 *
 * @package AppBundle\Controller
 * @subpackage
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Account;
use Symfony\Component\HttpFoundation\Request;

/**
 * Handle Transaction page
 *
 * @Route("/transactions")
 * @package AppBundle\Controller
 * @subpackage
 * @author  Tom Jenkins <techguytom@nerdery.com>
 */
class TransactionController extends Controller
{
    /**
     * Handle page view
     *
     * @Method({"GET", "POST"})
     * @Route("/", name="transaction")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $user          = $this->get('security.token_storage')
                              ->getToken()
                              ->getUser();
        $em            = $this->getDoctrine()
                              ->getManager();
        $account       = new Account();
        $filterForm    = $this->createForm('accountFilter', $account);
        $reconcileForm = $this->createForm('accountFilter', $account, ['action' => $this->generateUrl('reconcile')]);

        $filterForm->handleRequest($request);

        if ($filterForm->isValid()) {
            $account       = $em->getRepository('AppBundle:Account')
                                ->findOneBy(['id' => $request->get('accountFilter')]);
            $transactions  = $em->getRepository('AppBundle:Transaction')
                                ->findBy(
                                    [
                                        'user'    => $user->getID(),
                                        'account' => $account
                                    ],
                                    ['date' => 'DESC']
                                );
            $reconcileForm = $this->createForm(
                'accountFilter',
                $account,
                ['action' => $this->generateUrl('reconcile')]
            );
            $flash         = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Now Viewing ' . $account->getName());
        } else {
            $transactions = $em->getRepository('AppBundle:Transaction')
                               ->findBy(['user' => $user->getID()], ['date' => 'DESC']);
        }

        return $this->render(
            'AppBundle:Transaction:transaction.html.twig',
            array(
                'filterForm'    => $filterForm->createView(),
                'reconcileForm' => $reconcileForm->createView(),
                'transactions'  => $transactions,
            )
        );
    }

    /**
     * Reconcile Transactions
     *
     * @Method({"GET", "POST"})
     * @Route("/reconcile", name="reconcile")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reconcileAction(Request $request)
    {
        $user        = $this->get('security.token_storage')
                            ->getToken()
                            ->getUser();
        $em          = $this->getDoctrine()
                            ->getManager();
        $account     = $em->getRepository('AppBundle:Account')
                          ->findOneBy(['id' => $request->get('accountFilter')]);
        $accountForm = $this->createForm('accountFilter', $account, ['action' => $this->generateUrl('reconcile')]);

        if (!$account) {
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->error('You must select an account to reconcile.');

            return $this->render(
                'AppBundle:Transaction:reconcile.html.twig',
                array(
                    'accountForm'   => $accountForm->createView(),
                    'reconcileForm' => null,
                    'transactions'  => null,
                )
            );
        }

        $transactions = $em->getRepository('AppBundle:Transaction')
                           ->findBy(
                               [
                                   'user'    => $user->getID(),
                                   'account' => $account
                               ],
                               [
                                   'date' => 'DESC'
                               ]
                           );
        $reconcileForm = $this->createForm('reconcile', $transactions);

        $reconcileForm->handleRequest($request);

        if ($reconcileForm->isValid()) {
            foreach ($accountForm->getData() as $reconciled) {
            }
        }

        return $this->render(
            'AppBundle:Transaction:reconcile.html.twig',
            array(
                'accountForm'   => $accountForm->createView(),
                'reconcileForm' => $reconcileForm->createView(),
                'transactions'  => $transactions,
            )
        );

    }
}
