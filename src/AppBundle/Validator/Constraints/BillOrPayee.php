<?php
/**
 * BillOrPayee
 * 
 * @package AppBundle\Validator\Constraints 

 * @subpackage
 */
 
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Register constraint for Transaction
 *
 * @package AppBundle\Validator\Constraints
 * @Annotation
 *
 * @subpackage
 * @author Tom Jenkins <tom@techguytom.com>
  */
class BillOrPayee extends Constraint
{
    public $message = 'You may only select a Bill or a Payee';

    public function validatedBy()
    {
        return 'bill_or_payee_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
