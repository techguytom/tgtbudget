<?php
/**
 * ReconcileType.php
 *
 * @package AppBundle\Form\Type
 * @subpackage
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\EntityRepository;

/**
 * Form for reconciling transactions
 *
 * @package AppBundle\Form\Type
 * @subpackage
 * @author  Tom Jenkins <tom@techguytom.com>
 */
class ReconcileType extends AbstractType
{
    protected $tokenStorage;

    public function __construct(TokenStorage $storage)
    {
        $this->tokenStorage = $storage;
    }

    /**
     * Builds reconcile form
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
                'reconciled',
                'entity',
                [
                    'class'         => 'AppBundle:Transaction',
                    'property'      => 'id',
                    'expanded'      => true,
                    'multiple'      => true,
                    'required'      => false,
                    'query_builder' => function (EntityRepository $er) use ($user, $options) {
                        if ($options['attr']['account']) {
                            return $er->createQueryBuilder('t')
                                      ->orderBy('t.date', 'DESC')
                                      ->where('t.user = :id', 't.account = :account')
                                      ->setParameters(
                                          [
                                              'id'      => $user->getId(),
                                              'account' => $options['attr']['account']
                                          ]
                                      );
                        }
                        return $er->createQueryBuilder('t')
                                  ->orderBy('t.date', 'DESC')
                                  ->where('t.user = :id')
                                  ->setParameter('id', $user->getId());
                    }
                ]
            )
            ->add(
                'account',
                'hidden',
                array(
                    'data' => $options['attr']['account'],
                )
            )
            ->add('reconcile', 'submit');
    }

    /**
     * Get name for the transaction form
     *
     * @return string
     */
    public function getName()
    {
        return 'reconcile';
    }

}
