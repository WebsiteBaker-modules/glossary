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
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }
// basically loads the $size array
include('info.php');

if (!defined('WYSIWYG_EDITOR') OR WYSIWYG_EDITOR=="none" OR !file_exists(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
	function show_wysiwyg_editor($name,$id,$content,$width,$height) {
		echo '<textarea name="'.$name.'" id="'.$id.'" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
	}
} else {
	$id_list=array("short","long");
		/**
		 *	Avoid to load the include.php of the currend wysiwyg-editor twice.
		 *	
		 */
		require_once(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
}

/**
 *	Load Language file
 */
$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

/**
 *	include the button to edit the optional module CSS files
 *	Note: CSS styles for the button are defined in backend.css (div class="mod_moduledirectory_edit_css")
 *	Place this call outside of any <form></form> construct!!!
 */
include_once(WB_PATH .'/framework/module.functions.php');
if(function_exists('edit_module_css')) {
	edit_module_css('glossary');
}

// Setup template object
$template = new Template(WB_PATH.'/modules/glossary');
$template->set_file('page', 'htt/form.htt');
$template->set_block('page', 'main_block', 'main');

ob_start();
		show_wysiwyg_editor(
			"definition".$section_id,
			"definition".$section_id,
			'',
			"100%",
			"300px"
		);
$editor = ob_get_clean();

/**
 *	We need the FTAN-string more than one time.
 *
 */
$ftan = method_exists($admin, "getFTAN") ? $admin->getFTAN() : "";
if($ftan == "") {
	/**
	 *	LEPTON?
	 */
	if (defined("LEPTON_GUID")) {
		$cf = dirname(__FILE__)."/classes/c_lepton_sec.php";
		if (file_exists( $cf ) ) {
			require_once( $cf );
			$c = new c_lepton_sec();
			$c->max_level = 3;
			$c->error_msg = $MOD_GLOSSARY['cant_find_class_sec'];
			$c->include_secure();
			unset( $c );
		}
		unset( $cf );
	}
}

// Insert vars
$template->set_var(
	array(
		'SAVE' => $TEXT['SAVE'],
		'CANCEL' => $TEXT['CANCEL'],
		'ADMIN_URL' => ADMIN_URL,
		'WB_URL' => WB_URL,
		'page_id' => $page_id,
		'section_id' => $section_id,
		'label_definition' => $MOD_GLOSSARY['label_definition'],
		'label_term' => $MOD_GLOSSARY['label_term'],
		'label_pronunciation' => $MOD_GLOSSARY['label_pronunciation'],
		'editor' => $editor,
		'term' => '',
		'term_id' => '',
		'pronunciation' => '',
		'ftan' =>  $ftan,
		'label_active' => $MOD_GLOSSARY['label_active'],
		'active_checked' => " checked='checked'",
		'save'	=> 'save.php',
		'leptoken' => isset($_GET['leptoken']) 
			? "<input type='hidden' name='leptoken' value='".$_GET['leptoken']."' />" 
			: ""
	)
);

// Parse template object
$template->set_unknowns('keep');
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page', false);

$query_termlist = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_".$module_directory."` WHERE section_id = '".$section_id."' ORDER BY `term` ASC");
$term_num = $query_termlist->numRows();

/**
 *	Try to get the correct path for the icons. If we're running under WB 2.7 - there is no THEME_URL
 *
 */
$icon_url = !defined('THEME_URL') ? ADMIN_URL : THEME_URL;

$template = new Template(WB_PATH.'/modules/glossary');
$template->set_file('page', 'htt/list.htt');
$template->set_unknowns('keep');

$template->set_block('page', 'main_block', 'main');
$template->set_block('main_block', 'tr_block', 'tr_list');

$template->set_var(
	array(
		'modify_delete' => $MOD_GLOSSARY['modify_delete'],
		'WB_URL' => WB_URL,
		'module_directory' => $module_directory,
		'section_id' => $section_id,
		'page_id'	=> $page_id,
		'ftan'	=> $ftan,
		'save'	=> "save.php",
		'leptoken' => isset($_GET['leptoken']) 
			? "<input type='hidden' name='leptoken' value='".$_GET['leptoken']."' />" 
			: ""
	)
);

while($term = $query_termlist->fetchRow( MYSQL_ASSOC )) { 
	$vars = array(
		'icon_url' => $icon_url,
		'term'	=> $term['term'],
		'pronunciation' => $term['pronunciation'],
		'definition' => 	strip_tags(substr($term['definition'], 0, 40)),
		'term_id' => $term['term_id'],
		'ARE_YOU_SURE' => sprintf( $MOD_GLOSSARY['are_you_sure'], $term['term'] ),
		'status' => ($term['active'] == "1" 
			? $MOD_GLOSSARY['status_active'] 
			: $MOD_GLOSSARY['status_inactive'])
			,
		'plus_or_minus' => ($term['active'] == "1" ? "visible_16.png" : "none_16.png"),
		'MODIFY'	=> $TEXT['MODIFY'],
		'DELETE'	=> $TEXT['DELETE']
	);
	
	$template->set_var( $vars );
	$template->parse('tr_list', 'tr_block', true);
}

$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

?>