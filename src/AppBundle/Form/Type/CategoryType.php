<?php
/**
 * CategoryType.php
 * 
 * @package AppBundle\Form\Type 

 * @subpackage
 */
 
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Category Form Type
 *
 * @package AppBundle\Form\Type 

 * @subpackage
 * @author Tom Jenkins <tom@techguytom.com>
  */
class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                'text',
                array(
                    'label' => 'Category Name',
                    'attr' => array(
                        'placeholder' => 'Entertainment',
                    ),
                    'required' => true
                )
            )
            ->add('save', 'submit', array('label' => 'Add'));
    }

    public function getName()
    {
        return 'category';
    }
}
