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
        $reconcileForm = $this->createForm('accountFilter', $account, ['action' => $this->generateUrl('transaction')]);

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
            $reconcileForm = $this->createForm('accountFilter', $account);
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
     * @Method("POST")
     * @ParamConverter("account", class="AppBundle:Account")
     * @param Account $account
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reconcileAction(Account $account, Request $request)
    {
        $em = $this->getDoctrine()
                   ->getManager();

        $transactions = $em->getRepository('AppBundle:Transaction')
                           ->findBy(['account' => $account], ['date' => 'DESC']);

        $form = $this->createForm('reconcile', $transactions);

        $form->handleRequest($request);
        if ($form->isValid()) {
            foreach ($form->getNormData()['id'] as $transaction) {
                $transaction->setReconciled(true);
                $em->persist($transaction);
            }
            $em->flush();
        }

        return $this->render(
            'AppBundle:Transaction:transaction.html.twig',
            array(
                'form'         => $form->createView(),
                'transactions' => $transactions,
            )
        );

    }
}
