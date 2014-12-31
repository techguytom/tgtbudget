<?php
/**
 * BillRepository.php
 *
 * @package AppBundle\Entity
 * @subpackage
 */

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Bill Repository
 *
 * @package    AppBundle\Entity
 * @subpackage Bill
 * @author     Tom Jenkins <tom@techguytom.com>
 */
class BillRepository extends EntityRepository
{
    /**
     * Query builder for types by user
     *
     * @param User $user
     *
     * @return QueryBuilder
     */
    public function queryByUser(User $user)
    {
        $query = $this->createQueryBuilder('t')
                      ->orderBy('t.name', 'ASC')
                      ->where('t.user = :id')
                      ->andWhere('t.paid = false')
                      ->setParameter('id', $user->getId());

        return $query;
    }

    /**
     * Get results by user
     *
     * @param User $user
     *
     * @return array
     */
    public function findByUser($user)
    {
        return $this->queryByUser($user)
                    ->getQuery()
                    ->getResult();
    }

    /**
     * Get all Unpaid Bills from one user
     *
     * @param int $id User Id
     *
     * @return array
     *
     */
    public function findAllUnPaidByUser($id)
    {
        return $this->createQueryBuilder('b')
                    ->orderBy('b.dueDate', 'ASC')
                    ->where('b.paid = false')
                    ->andWhere('b.user = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getResult();
    }
}
