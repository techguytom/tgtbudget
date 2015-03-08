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
        $account       = new Account();
        $transaction   = new Transaction();
        $filterForm    = $this->createForm('accountFilter', $account);
        $reconcileForm = $this->createForm('reconcile', $transaction, ['attr' => ['account' => null]]);

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
            $reconcileForm = $this->createForm('reconcile', $transaction, ['attr' => ['account' => $account->getId()]]);
            $flash         = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Now Viewing ' . $account->getName());

            return $this->render(
                'AppBundle:Transaction:transaction.html.twig',
                array(
                    'filterForm'    => $filterForm->createView(),
                    'reconcileForm' => $reconcileForm->createView(),
                    'transactions'  => $transactions,
                )
            );
        }

        $reconcileForm->handleRequest($request);

        if ($reconcileForm->isValid()) {
            $formData = $reconcileForm->getData();
            if ($formData->getAccount()) {
                $account       = $em->getRepository('AppBundle:Account')
                                    ->findOneBy(['id' => $formData->getAccount()]);
                $transactions  = $em->getRepository('AppBundle:Transaction')
                                    ->findBy(
                                        [
                                            'user'    => $user->getID(),
                                            'account' => $account
                                        ],
                                        ['date' => 'DESC']
                                    );
                $reconcileForm = $this->createForm(
                    'reconcile',
                    $transaction,
                    ['attr' => ['account' => $account->getId()]]
                );
                $flash = $this->get('braincrafted_bootstrap.flash');
                $flash->success('Now Viewing ' . $account->getName() . ' and account has been reconciled.');

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

        $transactions = $em->getRepository('AppBundle:Transaction')
                           ->findBy(['user' => $user->getID()], ['date' => 'DESC']);

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
