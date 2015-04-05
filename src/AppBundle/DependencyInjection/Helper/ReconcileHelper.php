<?php
/**
 * ReconcileHelper.php
 *
 * @package AppBundle\DependencyInjection\Helper
 * @subpackage
 */

namespace AppBundle\DependencyInjection\Helper;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Transaction;

/**
 * Helper class for reconciling transaction
 *
 * @package AppBundle\DependencyInjection\Helper
 * @subpackage
 * @author  Tom Jenkins <tom@techguytom.com>
 */
class ReconcileHelper
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Handle Dependency Injection
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Reconcile transaction login
     *
     * @param array $data
     * @param array $transactions
     *
     * @return bool
     */
    public function reconcileTransactions(Array $data, Array $transactions)
    {
        $ids = [];

        foreach ($data['id'] as $transaction) {
            array_push($ids, $transaction->getId());
            if (!$transaction->isReconciled()) {
                $transaction->setReconciled(1);
                $this->em->persist($transaction);
            }
        }

        foreach ($transactions as $transaction) {
            if (!in_array($transaction->getId(), $ids) && $transaction->isReconciled()) {
                $transaction->setReconciled(0);
                $this->em->persist($transaction);
            }
        }

        $this->em->flush();

        return true;
    }
}
