<?php
/**
 * [File description text goes here...]
 * 
 * @package AppBundle\Form\Type 

 * @subpackage
 */
 
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * [Class desc. text goes here...]
 *
 * @package AppBundle\Form\Type 

 * @subpackage
 * @author YOUR_NAME_HERE <techguytom@nerdery.com>
  */
class AccountTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                'text',
                array(
                    'label' => 'Account Type Name',
                    'attr' => array(
                        'placeholder' => 'Visa',
                    ),
                    'required' => true
                )
            )
            ->add('save', 'submit', array('label' => 'Add'));
    }

    public function getName()
    {
        return 'accountType';
    }
}
