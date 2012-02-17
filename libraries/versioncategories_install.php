<?php
/**
 * Version Categories - Install
 *
 * @author	   John Etherton
 * @package	   Version Categories
 */

class Versioncategories_Install {

	/**
	 * Constructor to load the shared database library
	 */
	public function __construct()
	{
		$this->db = Database::instance();
	}

	/**
	 * Creates the required database tables for the actionable plugin
	 */
	public function run_install()
	{
		// Create the database tables.
		// Also include table_prefix in name
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.Kohana::config('database.default.table_prefix').'versioncategories` (
				  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				  `incident_id` bigint(20) unsigned NOT NULL,
				  `category_id` int(11) unsigned NOT NULL,
				  `type` tinyint(4) default NULL COMMENT \'0 - removed, 1 - added\',				  
				  `time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,				  				  
				  PRIMARY KEY (`id`)				  
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

	}

	/**
	 * Deletes the database tables for the actionable module
	 */
	public function uninstall()
	{
		// I worry that someone will have tons of data saved, then carelessly click "deactiviate" and blow the whole thing.
		// So I make it harder than that.
	}
}
