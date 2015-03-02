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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form for reconciling transactions
 *
 * @package AppBundle\Form\Type
 * @subpackage
 * @author  Tom Jenkins <tom@techguytom.com>
 */
class ReconcileType extends AbstractType
{
    /**
     * Builds reconcile form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'reconciled',
                'entity',
                [
                    'class'    => 'AppBundle:Transaction',
                    'property' => 'id',
                    'expanded' => true,
                    'multiple' => true,
                    'required' => false,
                ]
            )
            ->add( /*TODO Find a way to have the account id sent in the post */
                'accountFilter',
                'hidden'
            )
            ->add('reconcile', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'      => null,
                'csrf_protection' => false
            )
        );
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
