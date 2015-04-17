<?php
/* 
----------------------------------------------------------------------------
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
--------------------------------------------------------------------------
Version History:

	1.2.3	2012-10-25
			- Bugfix for removing slashes. Causes in broken links e.g. internal "[wb-links]".
			
	1.2.2	2012-10-24
			- Bugfix inside save_term.php and save.php for correct fieldnames. (Thank to Karsten)
	
	1.2.1	2012-10-09
			- Bugfix for correct utf8 output (view.php)
			- Bugfix inside save.php for missing field-values.
			- LEPTON-CMS compatible.
			- Bugfix in upgrade-script; wrong fieldname
	
	1.2.0	2012-10-08
			- start to try to make the modul LEPTON-compatible.
			- bugfixes inside save.php and other files.
			- add leptoken
			- bugfix inside view.php (add missing 'term')
			
	1.1.3	2012-10-07
			- Bugfix for saving edit terms
			- Remove some typos and clean-up some language-keys/variable-names.
			- Add upgrade.php to the project.
			- Minor codecleanings and optimations.
			- Add class c_glossary_query.
			- Add active flag.
	
	1.1.2	2012-10-04
			- some bugfixes inside "modify.php" to avoid wysiwyg-editor conflicts
			- add FTAN support for WB 2.8.2
			- add guid
			- add language-support
			- add templates (form, list, view)
			- backend/frontend css

	1.1 - 2009-05-07
		* added: FCKEditor support
		* added: most form fields get escaped now to avoid mySQL syntax errors

	1.0 Initial Release Jerry Gigowski 

*/

$module_directory = 'glossary';
$module_name = 'Glossary';
$module_function = 'page';
$module_version = (defined('LEPTON_GUID') ? '1.2.3.0' : '1.23' );
$module_platform = (defined('LEPTON_GUID') ? '1.1' : '2.8' );
$module_license = "GNU GPL";
$module_guid = '24CA830C-F843-4750-97C8-A7EE4DE44CC3';
$module_author = 'Jerry Gigowski, Oliver Bethke, Dietrich Roland Pehlke (last)';
$module_description = 'This page type is designed for making a Glossary page. Based on Travis Huizenga\'s simple module.';
?>