<?php
/**
 * AccountRepository.php
 *
 * @package AppBundle\Entity
 * @subpackage
 */

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Model\Validation;

/**
 * Account Repository
 *
 * @package AppBundle\Entity
 * @subpackage
 * @author  Tom Jenkins <tom@techguytom.com>
 */
class AccountRepository extends EntityRepository implements Validation
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
                      ->setParameter('id', $user->getId());

        return $query;
    }

    /**
     * Get results by user
     *
     * @param $user
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
     * Find any entries that share a user and a name
     *
     * @param User   $user
     * @param string $name
     *
     * @return array
     */
    public function findByUserAndName(User $user, $name)
    {
        return $this->findBy(['user' => $user, 'name' => $name]);
    }

}
