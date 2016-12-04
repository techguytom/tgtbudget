<?php
/**
 * BillOrPayeeValidator
 *
 * @package AppBundle\Validator\Constraints
 * @subpackage
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validation logic
 *
 * @package AppBundle\Validator\Constraints
 * @subpackage
 * @author  Tom Jenkins <tom@techguytom.com>
 */
class BillOrPayeeValidator extends ConstraintValidator
{
    /**
     * validate
     *
     * @param Object     $object
     * @param Constraint $constraint
     *
     * @internal param string $value
     */
    public function validate($object, Constraint $constraint)
    {
        if ($object->getName() && $object->getBill()) {
            $this->context->buildViolation($constraint->message)
                          ->addViolation($constraint->message);
        }

        if (($object->getName() && !$object->getCategory()) || $object->getCategory() && !$object->getName()) {
            $this->context->buildValidation('You must select a category and a name together')
                          ->addViolation('You must select a category and a name together');
        }
    }
}
