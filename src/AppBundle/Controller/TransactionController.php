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
        $user    = $this->get('security.token_storage')
                        ->getToken()
                        ->getUser();
        $em      = $this->getDoctrine()
                        ->getManager();
        $account = new Account();
        $form    = $this->createForm('accountFilter', $account);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $account      = $em->getRepository('AppBundle:Account')
                               ->findOneBy(['id' => $request->get('accountFilter')]);
            $transactions = $em->getRepository('AppBundle:Transaction')
                               ->findBy(
                                   [
                                       'user'    => $user->getID(),
                                       'account' => $account
                                   ],
                                   ['date' => 'DESC']
                               );
            $flash        = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Now Viewing ' . $account->getName());
        } else {
            $transactions = $em->getRepository('AppBundle:Transaction')
                               ->findBy(['user' => $user->getID()], ['date' => 'DESC']);
        }

        return $this->render(
            'AppBundle:Transaction:transaction.html.twig',
            array(
                'form'         => $form->createView(),
                'transactions' => $transactions,
            )
        );
    }

    /**
     * View single account transactions
     *
     * @Method("GET")
     * @ParamConverter("account", class="AppBundle:Account")
     * @param Account $account
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewByAccountAction(Account $account)
    {
        $user     = $this->get('security.token_storage')
                         ->getToken()
                         ->getUser();
        $em       = $this->getDoctrine()
                         ->getManager();
        $accounts = $em->getRepository('AppBundle:Account')
                       ->findBy(['user' => $user->getID()]);

        return $this->render(
            'AppBundle:Transaction:transaction.html.twig',
            array(
                'transactions' => $transactions,
                'accounts'     => $accounts,
            )
        );
    }

    /**
     * Reconcile Transactions
     *
     * @param $id
     */
    public function reconcileAction($id)
    {
        $user = $this->get('security . token_storage')
                     ->getToken()
                     ->getUser();
        $em   = $this->getDoctrine()
                     ->getManager();

        $transactions = $em->getRepository('AppBundle:Transaction')
                           ->findBy(['user' => $user->getID()], ['date' => 'DESC']);

    }
}
