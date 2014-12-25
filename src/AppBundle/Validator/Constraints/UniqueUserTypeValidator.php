<?php
/**
 * UniqueUserTypeValidator
 * 
 * @package AppBundle\Validator\Constraints 

 * @subpackage
 */
 
namespace AppBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use AppBundle\Entity\AccountType;

/**
 * Validation logic
 *
 * @package AppBundle\Validator\Constraints 

 * @subpackage
 * @author Tom Jenkins <tom@thejenkinsweb.com>
  */
class UniqueUserTypeValidator extends ConstraintValidator
{
    private $em;

    /**
     * Constructor
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * validate
     *
     * @param string      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $repository = $this->em->getRepository('AppBundle:AccountType');
        $AccountType = $repository->findOneBy(['name' => $value]);

        if($AccountType) {
            $this->context->addViolation($constraint->message, ["%string" => $value]);
        }
    }

}
