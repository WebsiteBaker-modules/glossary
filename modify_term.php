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
require(WB_PATH.'/modules/admin.php');

// Get header and footer
$query_content = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_".$module_directory."` WHERE `term_id`= '".$term_id."'");
$fetch_content = $query_content->fetchRow( MYSQL_ASSOC );

$defintion = htmlspecialchars($fetch_content['definition']);

if (!defined('WYSIWYG_EDITOR') OR WYSIWYG_EDITOR=="none" OR !file_exists(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
	function show_wysiwyg_editor($name,$id,$content,$width,$height) {
		echo '<textarea name="'.$name.'" id="'.$id.'" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
	}
} else {
	$id_list=array("short","long");
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

/**
 *	Try to get the WYSIWYG-Editor HTML-Source - as the module itself use a "wild" echo/print
 *	we're in the need to buffer it first.
 */
ob_start();
		show_wysiwyg_editor(
			"definition",
			"definition",
			htmlspecialchars($fetch_content['definition']),
			"100%",
			"300px"
		);
$editor = ob_get_clean();

// Setup template object
$template = new Template(WB_PATH.'/modules/glossary');
$template->set_file('page', 'htt/form.htt');
$template->set_block('page', 'main_block', 'main');

// Insert vars
$template->set_var(
	array(
		'SAVE' => $TEXT['SAVE'],
		'CANCEL' => $TEXT['CANCEL'],
		'ADMIN_URL' => ADMIN_URL,
		'WB_URL' => WB_URL,
		'page_id' => $page_id,
		'section_id' => $section_id,
		'term_id'	=> $fetch_content['term_id'],
		'label_definition' => $MOD_GLOSSARY['label_definition'],
		'label_term' => $MOD_GLOSSARY['label_term'],
		'label_pronunciation' => $MOD_GLOSSARY['label_pronunciation'],
		'label_active' => $MOD_GLOSSARY['label_active'],
		'active_checked' => $fetch_content['active'] == "1" ? "checked='checked'" :"",
		'editor' => $editor,
		'term' => $fetch_content['term'],
		'pronunciation' => $fetch_content['pronunciation'],
		'ftan' =>  method_exists($admin, "getFTAN") ? $admin->getFTAN() : "",
		'save' => "save_term.php",
		'leptoken' => isset($_GET['leptoken']) 
			? "<input type='hidden' name='leptoken' value='".$_GET['leptoken']."' />" 
			: ""
	)
);

// Parse template object
$template->set_unknowns('keep');
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page', false);

$admin->print_footer();

?>