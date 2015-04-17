<?php

/* -------------------------------------------------------------------------
			Website Baker project <http://www.websitebaker.org/>
					Copyright (C) 2004 - Ryan Djurovich
----------------------------------------------------------------------------
 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
--------------------------------------------------------------------------*/

if(defined('WB_URL')) {

	include('info.php');
	
	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_".$module_directory."`");
	
	$mod_create_table = 'CREATE TABLE `'.TABLE_PREFIX.'mod_'.$module_directory.'` ( '
		. '`term_id` INT NOT NULL AUTO_INCREMENT,'
		. '`section_id` INT NOT NULL,'
		. '`page_id` INT NOT NULL,'
		. '`term` VARCHAR(255) NOT NULL,'
		. '`pronunciation` VARCHAR(255) NOT NULL,'
		. '`definition` TEXT NOT NULL,'
		. '`active` INT NOT NULL default \'1\','
		. 'PRIMARY KEY (term_id)'
		. ' )';
				
	$database->query($mod_create_table);
	
	// Insert info into the search table
	// Module query info
	$field_info = array();
	$field_info['page_id'] = 'page_id';
	$field_info['title'] = 'page_title';
	$field_info['link'] = 'link';
	$field_info = serialize($field_info);
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('module', '$module_directory', '$field_info')");
	// Query start
	$query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title, [TP]mod_$module_directory.term, [TP]mod_$module_directory.definition, [TP]pages.link FROM [TP]mod_$module_directory, [TP]pages WHERE ";
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_start', '$query_start_code', '$module_directory')");
	// Query body
	$query_body_code = " [TP]pages.page_id = [TP]mod_$module_directory.page_id AND [TP]mod_$module_directory.term [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\'
	OR [TP]pages.page_id = [TP]mod_$module_directory.page_id AND [TP]mod_$module_directory.definition [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\'";
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_body', '$query_body_code', '$module_directory')");
	// Query end
	$query_end_code = "";	
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_end', '$query_end_code', '$module_directory')");
	
	// Insert blank row (there needs to be at least on row for the search to work
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_$module_directory (page_id,section_id) VALUES ('0','0')");
		
}

?>