<?php
/**
 * @package    Cluster
 *
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView;

/**
 * Clusters view
 *
 * @since  1.0.0
 */
class ClusterViewClusterUsers extends HtmlView
{
	/**
	 * An array of items
	 *
	 * @var  array
	 */
	protected $items;

	/**
	 * The pagination object
	 *
	 * @var  JPagination
	 */
	protected $pagination;

	/**
	 * The model state
	 *
	 * @var  object
	 */
	protected $state;

	/**
	 * Form object for search filters
	 *
	 * @var  JForm
	 */
	public $filterForm;

	/**
	 * Logged in User
	 *
	 * @var  JObject
	 */
	public $user;

	/**
	 * The active search filters
	 *
	 * @var  array
	 */
	public $activeFilters;

	/**
	 * The sidebar markup
	 *
	 * @var  string
	 */
	protected $sidebar;

	/**
	 * An ACL object to verify user rights.
	 *
	 * @var    CMSObject
	 * @since  1.0.0
	 */
	protected $canDo;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->user            = Factory::getUser();
		$this->canDo         = JHelperContent::getActions('com_cluster');

		ClusterHelper::addSubmenu('clusterusers');
		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.0.0
	 */
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_CLUSTERS_VIEW_CLUSTERS'), '');

		if ($this->canDo->get('core.create'))
		{
			JToolbarHelper::addNew('clusteruser.add');
		}

		if ($this->canDo->get('core.edit'))
		{
			JToolbarHelper::editList('clusteruser.edit');
		}

		if ($this->canDo->get('core.edit.state'))
		{
			JToolbarHelper::divider();
			JToolbarHelper::publish('clusteruser.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('clusteruser.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolBarHelper::archiveList('clusteruser.archive', 'JTOOLBAR_ARCHIVE');
			JToolbarHelper::divider();
		}

		if ($this->canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'clusterusers.delete', 'JTOOLBAR_DELETE');
			JToolbarHelper::divider();
		}

		if ($this->canDo->get('core.admin') || $this->canDo->get('core.options'))
		{
			JToolbarHelper::preferences('com_cluster');
			JToolbarHelper::divider();
		}
	}

	/**
	 * Method to order fields
	 *
	 * @return array
	 */
	protected function getSortFields()
	{
		return array(
			'cl.id' => JText::_('JGRID_HEADING_ID'),
			'cl.title' => JText::_('COM_CLUSTER_LIST_CLUSTERS_NAME'),
			'cl.client' => JText::_('COM_CLUSTER_LIST_CLUSTERS_CLIENT'),
			'cl.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'cl.state' => JText::_('JSTATUS'),
		);
	}
}
