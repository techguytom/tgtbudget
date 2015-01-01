<?php
/**
 * BillType.php
 *
 * @package AppBundle\Form\Type
 * @subpackage
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Bill Type Form
 *
 * @package AppBundle\Form\Type
 * @subpackage
 * @author  Tom Jenkins <tom@techguytom.com>
 */
class BillType extends AbstractType
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
                    'label'    => 'Bill Name',
                    'attr'     => array(
                        'placeholder' => 'Rent',
                    ),
                    'required' => true
                )
            )
            ->add(
                'category',
                'entity',
                array(
                    'label'         => 'Budget Category',
                    'class'         => 'AppBundle\Entity\Category',
                    'property'      => 'name',
                    'placeholder'   => 'Select A Budget Category...',
                    'required'      => true,
                    'query_builder' => function (CategoryRepository $categoryRepository) use ($user) {
                        return $categoryRepository->queryByUser($user);
                    }
                )
            )
            ->add(
                'budgetAmount',
                'tbbc_money',
                array(
                    'label'    => 'Amount',
                    'required' => true
                )
            )
            ->add(
                'dueDate',
                'date',
                array(
                    'label'    => 'Next Monthly Due Date',
                    'required' => true
                )
            )
            ->add(
                'recurring',
                'checkbox',
                array(
                    'label'    => "Monthly Recurring Bill",
                    'required' => false
                )
            )
            ->add('save', 'submit', array('label' => 'Add'));
    }

    public function getName()
    {
        return 'bill';
    }

}
