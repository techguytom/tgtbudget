<?php
/**
 * BillOrPayeeValidator
 *
 * @package AppBundle\Validator\Constraints
 * @subpackage
 */

namespace AppBundle\Validator\Constraints;

use Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage;
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
     * @var FlashMessage $flashMessage
     */
    private $flashMessage;

    public function __construct(FlashMessage $flashMessage)
    {
        $this->flashMessage = $flashMessage;
    }

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
            $this->flashMessage->info($constraint->message);
            $this->context->addViolation($constraint->message);
        }

        if (($object->getName() && !$object->getCategory()) || $object->getCategory() && !$object->getName()) {
            $this->flashMessage->info('You must select a category and a name together');
            $this->context->addViolation('You must select a category and a name together');
        }
    }
}
