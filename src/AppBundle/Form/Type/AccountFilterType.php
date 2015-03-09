<?php
/**
 * AccountFilterType.php
 * 
 * @package AppBundle\Form\Type 

 * @subpackage
 */
 
namespace AppBundle\Form\Type;

use AppBundle\Entity\AccountRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Filter selector for Accounts
 *
 * @package AppBundle\Form\Type 

 * @subpackage
 * @author Tom Jenkins <tom@techguytom.com>
  */
class AccountFilterType extends AbstractType
{
    protected $tokenStorage;

    public function __construct(TokenStorage $storage)
    {
        $this->tokenStorage = $storage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->tokenStorage->getToken()
                                   ->getUser();

        $builder
            ->add(
                'type',
                'entity',
                array(
                    'class'         => 'AppBundle\Entity\Account',
                    'property'      => 'name',
                    'placeholder'   => 'Filter By Account',
                    'required'      => true,
                    'query_builder' => function (AccountRepository $accountRepository) use ($user) {
                        return $accountRepository->queryByUser($user);
                    }
                )
            )
            ->add('filter', 'submit');
    }

    public function getName()
    {
        return 'accountFilter';
    }
}
