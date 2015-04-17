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

require('../../config.php');

include('info.php');

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
		$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	}
}

/**
 *	Load Language file
 */
$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

// Validate some fields
if( ($admin->get_post('term') == '') || ($admin->get_post('pronunciation') == '') ) {
	$admin->print_error(
		$MESSAGE['GENERIC']['FILL_IN_ALL'],
		WB_URL.'/modules/'.$module_directory.'/modify_term.php?page_id='.$page_id.'&section_id='.$section_id
	);
}

require_once(dirname( __FILE__)."/classes/c_glossary_query.php" );
$gq = new c_glossary_query();

$vars = array(
	'term'			=> mysql_real_escape_string($admin->get_post_escaped('term')),
	'pronunciation'	=> mysql_real_escape_string($admin->get_post_escaped('pronunciation')),
	'definition'	=> mysql_real_escape_string($admin->get_post_escaped('definition'.$section_id)),
	'active'		=> mysql_real_escape_string($admin->get_post_escaped('active')),
	'section_id'	=> $admin->get_post('section_id'),
	'page_id'		=> $admin->get_post('page_id')
);

$database->query( $gq->build_mysql_query(
	'insert',
	TABLE_PREFIX."mod_glossary",
	$vars
	)
);

// Check if there is a database error, otherwise say successful
if($database->is_error()) {
	$admin->print_error(
		$database->get_error(),
		$js_back
	);
} else {
	$admin->print_success(
		sprintf($MOD_GLOSSARY['term_saved'], $vars['term']),
		ADMIN_URL.'/pages/modify.php?page_id='.$page_id
	);
}

// Print admin footer
$admin->print_footer()

?>