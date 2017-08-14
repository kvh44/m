<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class MpostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('userId', HiddenType::class, array('required' => true))
                ->add('internalId')
                ->add('category')
                ->add('draft')
                ->add('title')
                ->add('description', TextareaType::class)
                ->add('isZh')
                ->add('isEn')
                ->add('isFr')
                ->add('displayedHome')
                ->add('viewNumber')
                ->add('isFromOtherWeb')
                ->add('otherWeb')
                ->add('isDeleted')
                ->add('deletedByUserId')
                ->add('isSynchronizedByCache')
                ->add('isSynchronizedBySearch')
                ->add('slug')
                ->add('created')
                ->add('updated')     
                ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\Mpost'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'adminbundle_mpost';
    }


}
