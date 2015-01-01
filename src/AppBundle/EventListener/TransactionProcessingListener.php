<?php
/**
 * BillProcessingListener.php
 *
 * @package AppBundle\Form\EventListener
 * @subpackage
 */

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Transaction;
use AppBundle\Entity\Bill;

/**
 * Perform bill processing
 *
 * @package AppBundle\Form\EventListener
 * @subpackage
 * @author  Tom Jenkins <tom@techguytom.com>
 */
class TransactionProcessingListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity        = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Transaction) {
            if ($entity->getBill()) {
                $entity->getBill()->setPaid(true);
                $newBalance = $entity->getAccount()
                                     ->getCurrentBalance()
                                     ->subtract($entity->getTransactionAmount());
                $entity->getAccount()
                       ->setCurrentBalance($newBalance);
                $bill = $entity->getBill();
                if ($bill->isRecurring()) {
                    $currentDueDate = $bill->getDueDate();
                    $newDueDate     = $currentDueDate->modify('+1 month');
                    $newBill        = new Bill();
                    $newBill->setUser($bill->getUser());
                    $newBill->setName($bill->getName());
                    $newBill->setAccounts($bill->getAccounts());
                    $newBill->setDueDate($newDueDate);
                    $newBill->setBudgetAmount($bill->getBudgetAmount());
                    $newBill->setCategory($bill->getCategory());
                    $newBill->setRecurring(true);
                    $newBill->setPaid(false);
                    $entityManager->persist($newBill);
                }
            }
            if ($entity->getCategory()) {
                $newBalance = $entity->getAccount()
                                     ->getCurrentBalance()
                                     ->add($entity->getTransactionAmount());
                $entity->getAccount()
                       ->setCurrentBalance($newBalance);
            }
            if ($entity->getName() === 'Deposit') {
                $newBalance = $entity->getAccount()
                                     ->getCurrentBalance()
                                     ->add($entity->getTransactionAmount());
                $entity->getAccount()
                       ->setCurrentBalance($newBalance);
            }
            if ($entity->getName() === 'Withdrawl') {
                $newBalance = $entity->getAccount()
                                     ->getCurrentBalance()
                                     ->subtract($entity->getTransactionAmount());
                $entity->getAccount()
                       ->setCurrentBalance($newBalance);
            }
        }

    }
}
