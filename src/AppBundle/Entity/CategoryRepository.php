<?php
/**
 * CategoryRepository.php
 * 
 * @package AppBundle\Entity 

 * @subpackage
 */
 
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use AppBundle\Model\Validation;

/**
 * Category Repository
 *
 * @package AppBundle\Entity
 * @subpackage
 * @author Tom Jenkins <tom@techguytom.com>
  */
class CategoryRepository extends EntityRepository implements Validation
{

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
