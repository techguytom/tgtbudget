<?php
/**
 * AppController.php
 *
 * @package AppBundle\Controller
 * @subpackage
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Transaction;
use Symfony\Component\HttpFoundation\Request;

/**
 * Handles public facing views and forms
 *
 * @package AppBundle\Controller
 */
class AppController extends Controller
{
    /**
     * Home Page
     *
     * @param Request $request
     * @Route("/", name="homepage")
     * @Method({"GET", "POST"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $transaction     = new Transaction();
        $transactionForm = $this->createForm('transaction', $transaction);
        $depositForm     = $this->createForm('deposit');
        $em              = $this->getDoctrine()
                                ->getManager();
        $user            = $this->get('security.token_storage')
                                ->getToken()
                                ->getUser();
        $flash           = $this->get('braincrafted_bootstrap.flash');

        $transactionForm->handleRequest($request);

        if ($transaction->getName() && $transaction->getBill()) {
            $flash->error('You may only select a Bill or a Payee');
            return $this->render(
                'AppBundle::index.html.twig',
                array(
                    'transactionForm' => $transactionForm->createView(),
                )
            );
        }

        if ($transactionForm->isValid()) {
            $transaction->setUser($user);
            $em->persist($transaction);
            $em->flush();
            $flash->success('Transaction Saved');
            $transaction     = new Transaction();
            $transactionForm = $this->createForm('transaction', $transaction);
        }

        if ($request->get('deposit')) {
            $depositForm->handleRequest($request);
            $data    = $depositForm->getData();
            $deposit = new Transaction();
            $deposit->setName('Deposit');
            $deposit->setDate($data['date']);
            $deposit->setAccount($data['toAccount']);
            $deposit->setTransactionAmount($data['transactionAmount']);
            $deposit->setUser($user);
            $em->persist($deposit);
            $em->flush();
            $flash->success('Deposit Completed');
// TODO: handle account balance changes throughout
            if ($data['fromAccount']) {
                $transfer = new Transaction();
                $transfer->setName('Transfer');
                $transfer->setDate($data['date']);
                $transfer->setAccount($data['fromAccount']);
                $transfer->setTransactionAmount($data['transactionAmount']);
                $transfer->setUser($user);
                $em->persist($transfer);
                $em->flush();
                $flash->success('Transfer Completed');
            }

            $depositForm = $this->createForm('deposit');
        }

        $accountRepository = $em->getRepository('AppBundle:Account');
        $accounts          = $accountRepository->findBy(['user' => $user->getId()]);
        $billRepository    = $em->getRepository('AppBundle:Bill');
        $bills             = $billRepository->findAllUnPaidByUser($user->getId());

        return $this->render(
            'AppBundle::index.html.twig',
            array(
                'transactionForm' => $transactionForm->createView(),
                'transferForm'    => $depositForm->createView(),
                'accounts'        => $accounts,
                'bills'           => $bills,
            )
        );
    }
}
