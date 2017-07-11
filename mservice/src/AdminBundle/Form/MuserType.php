<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MuserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('username')
                ->add('email')
                ->add('telephone')
                ->add('nickname')
                ->add('wechat')
                ->add('facebook')
                ->add('instagram')
                ->add('website')
                ->add('timezone')
                ->add('country')
                ->add('city')
                ->add('postNumber')
                //->add('countryId')
                //->add('locationId')
                ->add('skinColor')
                ->add('weight')
                ->add('height')
                ->add('birthday')
                ->add('hourPrice')
                ->add('hourPriceUnit')
                ->add('nightPrice')
                ->add('nightPriceUnit')
                ->add('shopAddress')
                ->add('shopName')
                ->add('description')
                ->add('translatedDescription')
                ->add('isActive')
                ->add('isDeleted')
                ->add('isPremium')
                ->add('isSingle')
                ->add('isShop')
                ->add('isZh')
                ->add('isEn')
                ->add('isFr')
                ->add('isAdmin')
                ->add('isTest')
                ->add('isSynchronizedByCache')
                ->add('isSynchronizedBySearch')
                ->add('isFromOtherWeb')
                ->add('otherWeb')
                ->add('otherWebReference')
                ->add('draftId')
                ->add('viewNumber')
                ->add('slug')
                ->add('token')
                ->add('internalToken')
                ->add('externalToken')
                ->add('internalId')
                ->add('topTime')
                ->add('lastSynchronizedFromOtherWebTime')
                ->add('paymentExpiredTime')
                ->add('allowedIp')
                ->add('created')
                ->add('updated')
                ->add('mcountry')
                ->add('mlocation')        
                ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\Muser'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'adminbundle_muser';
    }


}
