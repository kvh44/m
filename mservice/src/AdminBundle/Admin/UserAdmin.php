<?php
namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class UserAdmin extends AbstractAdmin
{
    protected $translationDomain = 'AdminBundle'; 
    
    // Fields to be shown on create/edit forms
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
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
            ->add('countryId', 'sonata_type_model', array(
                'class' => 'ApiBundle\Entity\Mcountry',
                'property' => 'countryEn'
            ))
            ->add('locationId', 'sonata_type_model', array(
                'class' => 'ApiBundle\Entity\Mlocation',
                'property' => 'cityEn'
            ))
            ->add('skinColor')   
            ->add('weight')  
            ->add('height')
            ->add('birthday', 'date', array('years'=> range(1970, (date("Y") - 18)), 'format' => 'yyyy MM dd'))
            ->add('hourPrice')  
            ->add('hourPriceUnit')    
            ->add('nightPrice')  
            ->add('nightPriceUnit')    
            ->add('shopAddress')
            ->add('shopName')   
            ->add('description', 'textarea', array('required' => false))
            ->add('translatedDescription', 'textarea', array('required' => false))
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
            ->add('isFromOtherWeb')
            ->add('otherWeb')  
            ->add('otherWebReference') 
            ->add('draftId', 'sonata_type_model', array(
                'class' => 'ApiBundle\Entity\Mdraft',
                'property' => 'link'
            )) 
            ->add('viewNumber')
            ->add('topTime', 'datetime', array('years'=> range(2017, date("Y"))))
            ->add('lastSynchronizedFromOtherWebTime', 'datetime', array('years'=> range(2017, date("Y"))))  
            ->add('paymentExpiredTime', 'datetime', array('years'=> range(2017, date("Y")))) 
            ->add('updated', 'datetime', array('years'=> range(2017, date("Y"))))
            ->add('created', 'datetime', array('years'=> range(2017, date("Y"))))     
       ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
       $datagridMapper
            ->add('username')
            ->add('email')
            ->add('telephone')
            ->add('wechat')   
       ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
            ->add('email')
            ->add('telephone')
            ->add('wechat') 
            ->add('updated')
            ->add('created')    
       ;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
           ->add('username')
           ->add('email')
           ->add('telephone')
           ->add('wechat') 
       ;
    }
    
    public function getAccess()
    {
        $listAccess = parent::getAccess();
        unset($listAccess['delete']);
        return $listAccess;
    }        
}