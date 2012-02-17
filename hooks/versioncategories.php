<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Version Categories - sets up the hooks
 *
 * @author	   John Etherton
 * @package	   Version Categories
 */

class versioncategories {
	
	/**
	 * Registers the main event add method
	 */
	 
	 
	public function __construct()
	{
		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));

		
	}
	
	/**
	 * Adds all the events to the main Ushahidi application
	 */
	public function add()
	{
		Event::add('ushahidi_action.report_categories_changing', array($this, '_save_data'));					
		Event::add('ushahidi_action.report_categories_adding', array($this, '_add_cats'));			
		Event::add('ushahidi_action.report_categories_removing', array($this, '_remove_cats'));			
	}
	
	
	/**
	 * This function is called when we want to just tell
	 * the plugin that old categories X,Y, and Z are being removed
	 * to report A
	 */ 
	public function _remove_cats()
	{
		$data = Event::$data;
		$incident_id = $data['id'];
		$cats_to_remove = $data['categories'];
		foreach($cats_to_remove as $cat)
		{
				$entry = ORM::factory('versioncategories');
				$entry->category_id = $cat;
				$entry->incident_id = $incident_id;
				$entry->type = 0;
				$entry->save();
		}
	}
	
	/**
	 * This function is called when we want to just tell
	 * the plugin that new categories X,Y, and Z are being added
	 * to report A
	 */ 
	public function _add_cats()
	{
		$data = Event::$data;
		$incident_id = $data['id'];
		$cats_to_add = $data['categories'];
		foreach($cats_to_add as $cat)
		{
				$entry = ORM::factory('versioncategories');
				$entry->category_id = $cat;
				$entry->incident_id = $incident_id;
				$entry->type = 1;
				$entry->save();
		}
	}
	
	
	/**
	 * This will save the changes to the categories
	 */
	public function _save_data()
	{
		//pull the data from the event
		$data = Event::$data;
		$id = $data['id'];
		$new_cats = $data['new_categories'];
		
		
		//get the old categories
		$old_cats = ORM::factory('incident_category')->where('incident_id', $id)->find_all();
		
		//check out what's being removed
		foreach($old_cats as $old_cat)
		{
			$not_there_any_more = true;
			//if the old cat isn't in the new cats, then it's getting axed
			foreach($new_cats as $new_cat)
			{
				if($old_cat->category_id == $new_cat)
				{
					$not_there_any_more = false;
					break;
				}
			}
			if($not_there_any_more)
			{
				$data = ORM::factory('versioncategories');
				$data->category_id = $old_cat->category_id;
				$data->incident_id = $id;
				$data->type = 0;
				$data->save();
			}
		}
		//check what new categories are being added
		foreach($new_cats as $new_cat)
		{
			$is_new = true;
			//if the old cat isn't in the new cats, then it's getting axed
			foreach($old_cats as $old_cat)
			{
				if($old_cat->category_id == $new_cat)
				{
					$is_new = false;
					break;
				}
			}
			if($is_new)
			{
				$data = ORM::factory('versioncategories');
				$data->category_id = $new_cat;
				$data->incident_id = $id;
				$data->type = 1;
				$data->save();
			}
		}
		
	}//end _save_data
}

new versioncategories;
