<?php
 
namespace Tperroin\TremplinFoyerBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Knp\Menu\ItemInterface as MenuItemInterface;
 
class TremplinAdmin extends Admin
{
    
    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('active')
            ->add('titre')
            ->add('image')
            ->add('teaser')
            ->add('contenu')
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('active', 'checkbox', array('required' => false))
                ->add('titre')
                ->add('image', 'sonata_type_model') 
                ->add('teaser', 'textarea', array('required' => false,'attr' => array('class' => 'ckeditor')))
                ->add('contenu', 'textarea', array('required' => false,'attr' => array('class' => 'ckeditor')))
            ->end()
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('titre')
            ->add('active')
            ->add('image')
            ->add('teaser')
            ->add('contenu')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'view' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    
    
}
