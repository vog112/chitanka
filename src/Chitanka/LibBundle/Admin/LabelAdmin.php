<?php
namespace Chitanka\LibBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class LabelAdmin extends Admin
{
	protected $baseRoutePattern = 'label';
	protected $baseRouteName = 'admin_label';
	protected $translationDomain = 'admin';

	protected function configureShowField(ShowMapper $showMapper)
	{
		$showMapper
			->add('name')
			->add('slug')
			->add('parent')
			->add('nr_of_texts')
			->add('children')
			->add('texts')
		;
	}

	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
			->addIdentifier('name')
			->add('slug')
			->add('_action', 'actions', array(
				'actions' => array(
					'view' => array(),
					'edit' => array(),
					'delete' => array(),
				)
			))
		;
	}

	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
			->with('General attributes')
				->add('name')
				->add('slug')
				->add('parent', null, array('required' => false, 'query_builder' => function ($repo) {
					return $repo->createQueryBuilder('e')->orderBy('e.name');
				}))
			->end()
		;

	}

	protected function configureDatagridFilters(DatagridMapper $datagrid)
	{
		$datagrid
			->add('slug')
			->add('name')
		;
	}

}
