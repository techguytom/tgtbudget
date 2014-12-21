<?php
/**
 * Created by PhpStorm.
 * User: techguytom
 * Date: 12/21/14
 * Time: 11:55 AM
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * [Class desc. text goes here...]
 *
 * @package    AppBundle\Form\Type
 * @subpackage Symfony2
 * @author     Tom Jenkins <$tjenkins@nerdery.com>
 */
class AccountCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                'text',
                array(
                    'label' => 'Account Name',
                )
            )
            ->add(
                'type',
                'entity',
                array(
                    'class'       => 'AppBundle\Entity\AccountType',
                    'data_class'  => 'AppBundle\Entity\AccountType',
                    'property'    => 'name',
                    'placeholder' => 'Select An Account Type',
                )
            )
            ->add(
                'accountNumber',
                'integer',
                array(
                    'label' => 'Last 4 Digits of Account Number',
                )
            )
            ->add(
                'availableBalance',
                'tbbc_money',
                array(
                    'label' => 'Available Balance',
                )
            )
            ->add(
                'creditLine',
                'tbbc_money',
                array(
                    'label' => 'Credit Line',
                )
            )
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'accountCreate';
    }

}