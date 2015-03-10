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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Account;
use AppBundle\Entity\Transaction;

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
        $flash         = $this->get('braincrafted_bootstrap.flash');
        $account       = new Account();
        $filterForm    = $this->createForm('accountFilter', $account);
        $reconcileForm = $this->createForm('reconcile', null, ['attr' => ['account' => null]]);
        $transactions  = $em->getRepository('AppBundle:Transaction')
                            ->findBy(['user' => $user->getID()], ['date' => 'DESC']);

        $filterForm->handleRequest($request);

        if ($filterForm->isValid()) {
            $formData      = $filterForm->getData();
            $account       = $formData->getType();
            $transactions  = $em->getRepository('AppBundle:Transaction')
                                ->findBy(
                                    [
                                        'user'    => $user->getID(),
                                        'account' => $account
                                    ],
                                    [
                                        'date' => 'DESC'
                                    ]
                                );
            $reconcileForm = $this->createForm(
                'reconcile',
                null,
                [
                    'attr' => [
                        'account' => $account->getId()
                    ]
                ]
            );
            $flash->success('Now Viewing ' . $account->getName());
        }

        $reconcileForm->handleRequest($request);

        if ($reconcileForm->isValid()) {
            $formData = $reconcileForm->getData();
            if ($formData['account']) {
                $account      = $em->getRepository('AppBundle:Account')
                                   ->findOneBy(['id' => $formData['account']]);
                $transactions = $em->getRepository('AppBundle:Transaction')
                                   ->findBy(
                                       [
                                           'user'    => $user->getID(),
                                           'account' => $account
                                       ],
                                       ['date' => 'DESC']
                                   );
                $reconciled   = $this->get('app.reconcile.helper')
                                     ->reconcileTransactions($formData, $transactions);
                if ($reconciled) {
                    $flash->success($account->getName() . ' account has been reconciled.');

                } else {
                    $flash->error("There was an error while reconciling the " . $account->getName() . " account.");

                }
                $reconcileForm = $this->createForm(
                    'reconcile',
                    null,
                    [
                        'attr' => [
                            'account' => $account->getId()
                        ]
                    ]
                );

            } else {
                $transactions = $em->getRepository('AppBundle:Transaction')
                                   ->findBy(['user' => $user->getID()], ['date' => 'DESC']);
                $reconciled   = $this->get('app.reconcile.helper')
                                     ->reconcileTransactions($formData, $transactions);
                if ($reconciled) {
                    $flash->success('Transactions have been reconciled.');

                } else {
                    $flash->error('There was an error while reconciling your transactions.');

                }
            }
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
}
