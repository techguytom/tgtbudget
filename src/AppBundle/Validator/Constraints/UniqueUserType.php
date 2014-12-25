<?php
/**
 * UniqueUserType
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
 * @author Tom Jenkins <tom@thejenkinsweb.com>
  */
class UniqueUserType extends Constraint
{
    public $message = 'You have already created the "%string" account type.';

    public function validatedBy()
    {
        return 'unique_user_type_validator';
    }
}
