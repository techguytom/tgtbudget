<?php
/**
 * UniqueNameValidator
 *
 * @package AppBundle\Validator\Constraints
 * @subpackage
 */

namespace AppBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Validation logic
 *
 * @package AppBundle\Validator\Constraints
 * @subpackage
 * @author  Tom Jenkins <tom@thejenkinsweb.com>
 */
class UniqueNameValidator extends ConstraintValidator
{
    private $entityManager;
    protected $tokenStorage;

    /**
     * Constructor
     *
     * @param EntityManager $entityManager
     * @param TokenStorage  $storage
     */
    public function __construct(EntityManager $entityManager, TokenStorage $storage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage  = $storage;
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
        $user       = $this->tokenStorage->getToken()
                                         ->getUser();
        $className  = $this->entityManager->getClassMetadata(get_class($object))
                                          ->getName();
        $repository = $this->entityManager->getRepository($className);
        $result     = $repository->findByUserAndName($user, $object->getName());

        if ($result) {
            $this->context->addViolation($constraint->message);
        }
    }

}
