<?php
/**
 * Created by PhpStorm.
 * User: techguytom
 * Date: 12/21/14
 * Time: 11:55 AM
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\AccountTypeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Account Create Type
 *
 * @package    AppBundle\Form\Type
 * @subpackage Symfony2
 * @author     Tom Jenkins <tom@techguytom.com>
 */
class AccountCreateType extends AbstractType
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
                'name',
                'text',
                array(
                    'label'    => 'Account Name',
                    'required' => true,
                )
            )
            ->add(
                'type',
                'entity',
                array(
                    'class'         => 'AppBundle\Entity\AccountType',
                    'property'      => 'name',
                    'placeholder'   => 'Select An Account Type',
                    'required'      => true,
                    'query_builder' => function (AccountTypeRepository $accountTypeRepository) use ($user) {
                        return $accountTypeRepository->queryByUser($user);
                    }
                )
            )
            ->add(
                'currentBalance',
                'tbbc_money',
                array(
                    'label'    => 'Current Balance',
                    'required' => true,
                )
            )
            ->add(
                'creditLine',
                'tbbc_money',
                array(
                    'label'    => 'Credit Line',
                    'required' => false,
                )
            )
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'accountCreate';
    }
}
