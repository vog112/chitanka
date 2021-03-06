<?php
namespace Chitanka\LibBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class SequenceAdmin extends Admin
{
	protected $baseRoutePattern = 'sequence';
	protected $baseRouteName = 'admin_sequence';
	protected $translationDomain = 'admin';

	public $extraActions = 'LibBundle:SequenceAdmin:extra_actions.html.twig';

	protected function configureShowField(ShowMapper $showMapper)
	{
		$showMapper
			->add('name')
			->add('slug')
			->add('publisher')
			->add('is_seqnr_visible')
			->add('books')
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
				->add('publisher', null, array('required' => false))
				->add('is_seqnr_visible', null, array('required' => false))
			->end()
		;

	}

	protected function configureDatagridFilters(DatagridMapper $datagrid)
	{
		$datagrid
			->add('slug')
			->add('name')
			->add('publisher')
			->add('is_seqnr_visible')
		;
	}

}
