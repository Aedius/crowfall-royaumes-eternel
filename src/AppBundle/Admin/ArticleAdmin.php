<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ArticleAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('titre', 'text');
        $formMapper->add('slug', 'text');
        $formMapper->add('content', 'textarea');
        $formMapper->add('image', 'text');
        $formMapper->add('published', 'sonata_type_boolean', ['transform' => true]);
        $formMapper->add('home', 'sonata_type_boolean', ['transform' => true]);
        $formMapper->add('category', 'entity', [
            'class' => 'AppBundle\Entity\Category',
            'choice_label' => 'name'
        ]);
        $formMapper->add('version', 'entity', [
            'class' => 'AppBundle\Entity\Version',
            'choice_label' => 'name'
        ]);
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('titre');
        $datagridMapper->add('published');
        $datagridMapper->add('category');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('titre');
        $listMapper->add('published');
        $listMapper->add('publishedAt');
        $listMapper->add('category');
    }
}