<?php
/**
 * AppController.php
 *
 * @package AppBundle\Controller
 * @subpackage
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @Route("/{id}", defaults={"id": null}, requirements={"id": "\d+"}, name="userHomepage")
     * @Method("GET")
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($id)
    {
        $transaction = new Transaction();
        if ($id) {
            $em = $this->getDoctrine()
                       ->getManager();
            if ($bill = $em->getRepository('AppBundle:Bill')
                           ->find($id)
            ) {
                $transaction->setBill($bill);
                $transaction->setTransactionAmount($bill->getBudgetAmount());
                $transaction->setAccount($bill->getPayFromAccount());
            }
        }
        $transactionForm = $this->createForm('transaction', $transaction);
        $depositForm     = $this->createForm('deposit');
        $em              = $this->getDoctrine()
                                ->getManager();
        $user            = $this->get('security.token_storage')
                                ->getToken()
                                ->getUser();
        $accounts        = $em->getRepository('AppBundle:Account')
                              ->findBy(['user' => $user->getId()]);
        $bills           = $em->getRepository('AppBundle:Bill')
                              ->findAllUnPaidByUser($user->getId());

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

    /**
     * Handle to Transaction form post action
     *
     * @Route("/", name="postTransaction")
     * @param Request $request
     * @Method("POST")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createTransactionAction(Request $request)
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

        if ($transactionForm->isValid()) {
            if ($transaction->getBill() && $transaction->getBill()->getPayToAccount()) {
                $paymentTransaction = new Transaction();
                $paymentTransaction->setTransactionAmount($transaction->getTransactionAmount());
                $paymentTransaction->setAccount($transaction->getBill()->getPayToAccount());
                $paymentTransaction->setDate($transaction->getDate());
                $paymentTransaction->setName("Deposit");
                $paymentTransaction->setUser($user);
            }
            $transaction->setUser($user);
            $em->persist($transaction);
            if (isset($paymentTransaction)) {
                $em->persist($paymentTransaction);
            }
            $em->flush();
            $flash->success('Transaction Saved');
            $transaction     = new Transaction();
            $transactionForm = $this->createForm('transaction', $transaction);
        }
        $accounts = $em->getRepository('AppBundle:Account')
                       ->findBy(['user' => $user->getId()]);
        $bills    = $em->getRepository('AppBundle:Bill')
                       ->findAllUnPaidByUser($user->getId());

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

    /**
     * Handle Deposit / Transfer form
     *
     * @Route("/deposit", name="postDeposit")
     * @param Request $request
     * @Method("POST")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createDepositAction(Request $request)
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
        if ($data['fromAccount']) {
            $transfer = new Transaction();
            $transfer->setName('Withdrawl');
            $transfer->setDate($data['date']);
            $transfer->setAccount($data['fromAccount']);
            $transfer->setTransactionAmount($data['transactionAmount']);
            $transfer->setUser($user);
            $em->persist($transfer);
            $em->flush();
            $flash->reset();
            $flash->success('Transfer Completed');
        }

        $depositForm = $this->createForm('deposit');
        $accounts    = $em->getRepository('AppBundle:Account')
                          ->findBy(['user' => $user->getId()]);
        $bills       = $em->getRepository('AppBundle:Bill')
                          ->findAllUnPaidByUser($user->getId());

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
