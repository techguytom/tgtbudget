<?php
/**
 * Validation.php
 * 
 * @package AppBundle\Model 

 * @subpackage
 */
 
namespace AppBundle\Model;

use AppBundle\Entity\User;

/**
 * Interface for UniqueNameValidator
 *
 * @package AppBundle\Model 

 * @subpackage
 * @author Tom Jenkins <tom@techguytom.com>
  */
interface Validation
{
    /**
     * Find any entries that share a user and a name
     *
     * @param User   $user
     * @param string $name
     *
     * @return array
     */
    public function findByUserAndName(User $user, $name);

}
