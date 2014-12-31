<?php
/**
 * TransactionType.php
 *
 * @package AppBundle\Form\Type
 * @subpackage
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use AppBundle\Entity\CategoryRepository;
use AppBundle\Entity\BillRepository;
use AppBundle\Entity\AccountRepository;
use AppBundle\Form\EventListener\BillProcessingListener;

/**
 * Form for making payments or budget transactions
 *
 * @package AppBundle\Form\Type
 * @subpackage
 * @author  Tom Jenkins <tom@techguytom.com>
 */
class TransactionType extends AbstractType
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @param TokenStorage $storage
     */
    public function __construct(TokenStorage $storage)
    {
        $this->tokenStorage = $storage;
    }

    /**
     * Builds transaction form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->tokenStorage->getToken()
                                   ->getUser();

        $builder
            ->add(
                'date',
                'date',
                array(
                    'label'    => 'Date',
                    'required' => true
                )
            )
            ->add(
                'name',
                'text',
                array(
                    'label'    => 'Payee',
                    'required' => false,
                )
            )
            ->add(
                'category',
                'entity',
                array(
                    'class'         => 'AppBundle\Entity\Category',
                    'property'      => 'name',
                    'placeholder'   => 'Select A Budget Category...',
                    'required'      => false,
                    'query_builder' => function (CategoryRepository $categoryRepository) use ($user) {
                        return $categoryRepository->queryByUser($user);
                    }
                )
            )
            ->add(
                'bill',
                'entity',
                array(
                    'class'         => 'AppBundle\Entity\Bill',
                    'property'      => 'name',
                    'placeholder'   => 'Select A Bill...',
                    'required'      => false,
                    'query_builder' => function (BillRepository $billRepository) use ($user) {
                        return $billRepository->queryByUser($user);
                    }
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
                'account',
                'entity',
                array(
                    'class'         => 'AppBundle\Entity\Account',
                    'property'      => 'name',
                    'placeholder'   => 'Select An Account...',
                    'required'      => true,
                    'query_builder' => function (AccountRepository $accountRepository) use ($user) {
                        return $accountRepository->queryByUser($user);
                    }
                )
            )
            ->add('save', 'submit');
    }

    /**
     * Get name for the transaction form
     *
     * @return string
     */
    public function getName()
    {
        return 'transaction';
    }

}
