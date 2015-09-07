<?php
/**
 * BillProcessingListener.php
 *
 * @package AppBundle\Form\EventListener
 * @subpackage
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Bill;
use AppBundle\Entity\Transaction;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Perform bill processing
 *
 * @package AppBundle\Form\EventListener
 * @subpackage
 * @author  Tom Jenkins <tom@techguytom.com>
 */
class TransactionProcessingListener
{
    /**
     * Perform extra processing on Transactions
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity        = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if (!($entity instanceof Transaction)) {
            return;
        }
        if ($entity->getBill()) {
            $entity->getBill()->setPaid(true);

            if ($entity->getAccount()
                       ->getType()
                       ->isCreditAccount()
            ) {
                $entity = $this->addBalance($entity);

            } else {
                $entity = $this->subtractBalance($entity);
            }

            $bill = $entity->getBill();

            if ($bill->isRecurring()) {
                $currentDueDate = $bill->getDueDate();
                $newDueDate     = $currentDueDate->modify('+1 month');
                $newBill        = new Bill();
                $newBill->setUser($bill->getUser());
                $newBill->setName($bill->getName());
                $newBill->setPayToAccount($bill->getPayToAccount());
                $newBill->setPayFromAccount($bill->getPayFromAccount());
                $newBill->setDueDate($newDueDate);
                $newBill->setBudgetAmount($bill->getBudgetAmount());
                $newBill->setCategory($bill->getCategory());
                $newBill->setRecurring(true);
                $newBill->setPaid(false);
                $entityManager->persist($newBill);
            }
        }

        if ($entity->getCategory()) {
            if ($entity->getAccount()
                       ->getType()
                       ->isCreditAccount()
            ) {
                $entity = $this->addBalance($entity);

            } else {
                $entity = $this->subtractBalance($entity);
            }

        }

        if ($entity->getName() === 'Deposit') {
            if ($entity->getAccount()
                       ->getType()
                       ->isCreditAccount()
            ) {
                $entity = $this->subtractBalance($entity);

            } else {
                $entity = $this->addBalance($entity);
            }

        }

        if ($entity->getName() === 'Withdrawl') {
            if ($entity->getAccount()
                       ->getType()
                       ->isCreditAccount()
            ) {
                $entity = $this->addBalance($entity);

            } else {
                $entity = $this->subtractBalance($entity);
            }
        }
    }

    /**
     * Add money to account balance
     *
     * @param Transaction $transaction
     *
     * @return Transaction
     */
    private function addBalance(Transaction $transaction)
    {
        $newBalance = $transaction->getAccount()
                                  ->getCurrentBalance()
                                  ->add($transaction->getTransactionAmount());
        $transaction->getAccount()->setCurrentBalance($newBalance);

        return $transaction;
    }

    /**
     * Subtract money from account balance
     *
     * @param Transaction $transaction
     *
     * @return Transaction
     */
    private function subtractBalance(Transaction $transaction)
    {
        $newBalance = $transaction->getAccount()
                                  ->getCurrentBalance()
                                  ->subtract($transaction->getTransactionAmount());
        $transaction->getAccount()->setCurrentBalance($newBalance);

        return $transaction;
    }
}
