<?php
/**
 * UniqueName
 * 
 * @package AppBundle\Validator\Constraints 

 * @subpackage
 */
 
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Register constraint for Account Type
 *
 * @package AppBundle\Validator\Constraints
 * @Annotation
 *
 * @subpackage
 * @author Tom Jenkins <tom@techguytom.com>
  */
class UniqueName extends Constraint
{
    public $message = 'You have already used this name.';

    public function validatedBy()
    {
        return 'unique_name_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
