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
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Account;

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
            $reconcileForm = $this->createForm('reconcile', $transactions);
            $flash         = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Now Viewing ' . $account->getName());
        } else {
            $transactions  = $em->getRepository('AppBundle:Transaction')
                                ->findBy(['user' => $user->getID()], ['date' => 'DESC']);
            $reconcileForm = $this->createForm('reconcile', $transactions);
        }

        $reconcileForm->handleRequest($request);
        if ($reconcileForm->isValid()) {
            foreach ($reconcileForm->getNormData()['id'] as $transaction) {
                $transaction->setReconciled(true);
                $em->persist($transaction);
            }
            $em->flush();

        }

        return $this->render(
            'AppBundle:Transaction:transaction.html.twig',
            array(
                'form'          => $form->createView(),
                'reconcileForm' => $reconcileForm->createView(),
                'transactions'  => $transactions,
            )
        );
    }
}
