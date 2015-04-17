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

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) die("Cannot access this file directly");

$query_termlist = $database->query("SELECT `term`,`pronunciation`,`definition` FROM `".TABLE_PREFIX."mod_glossary` WHERE `section_id`= '".$section_id."' AND `active`=1 ORDER BY `term` ASC");

if($query_termlist->numRows() > 0) {
	
	$template = new Template(WB_PATH.'/modules/glossary');
	$template->set_file('page', 'htt/view.htt');

	$template->set_block('page', 'main_block', 'main');
	$template->set_block('main_block', 'list_block', 'list_list');

	$letter = "";
	$last_letter = "";

	while($term = $query_termlist->fetchRow()) {
		$letter = strtoupper(utf8_encode($term['term']));
		$vars = array(
			'letter'		=> ($letter != $last_letter) ? $letter : "",
			'pronunciation'	=> $term['pronunciation'] != "" ? " (" . stripslashes($term['pronunciation']) . ")" : "",
			'definition'	=> stripslashes($term['definition']),
			'term'			=> stripslashes($term['term'])
		);
		$template->set_var( $vars );
		$template->parse('list_list', 'list_block', true);		
		
		$last_letter = $letter;
	}
	
	$template->parse('main', 'main_block', false);
	$template->pparse('output', 'page');

} else { 
	echo "Page content not found"; 
}

?>