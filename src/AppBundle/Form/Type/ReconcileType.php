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
                'reconcile',
                'entity',
                [
                    'required'      => false,
                    'class'         => 'AppBundle:Transaction',
                    'property'      => 'id',
                    'property_path' => '[id]',
                    'multiple'      => true,
                    'expanded'      => true,
                ]
            )
            ->add('Reconcile', 'submit');
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
