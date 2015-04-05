<?php
/**
 * DepositType.php
 *
 * @package AppBundle\Form\Type
 * @subpackage
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use AppBundle\Entity\AccountRepository;

/**
 * Transfer or Deposit Form Type
 *
 * @package AppBundle\Form\Type
 * @subpackage
 * @author  Tom Jenkins <tom@techguytom.com>
 */
class DepositType extends AbstractType
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @var Router
     */
    protected $router;

    public function __construct(TokenStorage $storage, Router $router)
    {
        $this->tokenStorage = $storage;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->tokenStorage->getToken()
                                   ->getUser();

        $builder
            ->setAction($this->router->generate('postDeposit'))
            ->add(
                'date',
                'date',
                array(
                    'label'    => 'Date',
                    'required' => true,
                    'data'     => new \DateTime(),
                )
            )
            ->add(
                'transactionAmount',
                'tbbc_money',
                array(
                    'label'    => 'Amount',
                    'required' => true,
                )
            )
            ->add(
                'toAccount',
                'entity',
                array(
                    'class'         => 'AppBundle\Entity\Account',
                    'property'      => 'name',
                    'placeholder'   => 'Deposit Account...',
                    'required'      => true,
                    'query_builder' => function (AccountRepository $accountRepository) use ($user) {
                        return $accountRepository->queryByUser($user);
                    }
                )
            )
            ->add(
                'fromAccount',
                'entity',
                array(
                    'class'         => 'AppBundle\Entity\Account',
                    'property'      => 'name',
                    'placeholder'   => 'Transfer Account...',
                    'required'      => false,
                    'query_builder' => function (AccountRepository $accountRepository) use ($user) {
                        return $accountRepository->queryByUser($user);
                    }
                )
            )
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'deposit';
    }
}
