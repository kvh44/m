<?php
namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class CountryAdmin extends AbstractAdmin
{
    protected $translationDomain = 'AdminBundle'; 
    
    // Fields to be shown on create/edit forms
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('countryZh')
            ->add('countryFr')
            ->add('countryEn')
            ->add('internalId')
            ->add('slug')    
       ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
       $datagridMapper
            ->add('countryZh')
            ->add('countryFr')
            ->add('countryEn') 
       ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('countryZh')
            ->add('countryFr')
            ->add('countryEn')
       ;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('countryZh')
            ->add('countryFr')
            ->add('countryEn')
            ->add('internalId')
            ->add('slug') 
       ;
    }
    
    public function getAccess()
    {
        $listAccess = parent::getAccess();
        unset($listAccess['delete']);
        return $listAccess;
    }        
}