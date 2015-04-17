<?php

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2005, Ryan Djurovich

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

*/

require('../../config.php');
include('info.php');

// Get id
if(!isset($_POST['term_id']) OR !is_numeric($_POST['term_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$term_id = $_POST['term_id'];
}

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

/**
 *	FTAN
 *
 */
if(method_exists($admin, "checkFTAN")) {
	if (!$admin->checkFTAN()) {
		$admin->print_header();
		$admin->print_error(
			$MESSAGE['GENERIC_SECURITY_ACCESS'], 
			ADMIN_URL.'/pages/modify.php?page_id='.$page_id
		);
	}
}

// Validate some fields
if(($admin->get_post('term') == '') || ($admin->get_post('pronunciation') == '')) {
	$admin->print_error(
		$MESSAGE['GENERIC']['FILL_IN_ALL'],
		WB_URL.'/modules/'.$module_directory.'/modify_term.php?page_id='.$page_id.'&section_id='.$section_id.'&term_id='.$term_id
	);
}

/**
 *	Load Language file
 */
$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

// Update row
require_once(dirname( __FILE__)."/classes/c_glossary_query.php" );
$gq = new c_glossary_query();

$vars = array(
	'term'			=> mysql_real_escape_string($admin->get_post_escaped('term')),
	'pronunciation' => mysql_real_escape_string($admin->get_post_escaped('pronunciation')),
	'definition'	=> mysql_real_escape_string($admin->get_post_escaped('definition')),
	'active'		=> $admin->get_post_escaped('active')
);

$database->query( $gq->build_mysql_query(
	'update',
	TABLE_PREFIX."mod_glossary",
	$vars,
	"`term_id`='".$term_id."'"
	)
);

unset($gq);

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error(
		$database->get_error(),
		WB_URL.'/modules/'.$module_directory.'/modify_term.php?page_id='.$page_id.'&section_id='.$section_id.'&term_id='.$term_id
	);
} else {
	$admin->print_success(
		sprintf($MOD_GLOSSARY['term_saved'], $vars['term']),
		ADMIN_URL.'/pages/modify.php?page_id='.$page_id
	);
}

// Print admin footer
$admin->print_footer();

?>