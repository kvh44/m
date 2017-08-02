<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
class MpasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('userId', HiddenType::class, array('required' => true))
                ->add('password')
                ->add('encryptionMethod', HiddenType::class, array('required' => true))
                ->add('salt', HiddenType::class, array('required' => true))
                ->add('indication', HiddenType::class, array('required' => true))
                ->add('internalId')
                ->add('created')
                ->add('updated')
                ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\Mpassword'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'adminbundle_mpassword';
    }
}