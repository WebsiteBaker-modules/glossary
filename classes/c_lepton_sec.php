<?
/**
 *	Experimental class study for a better secure-handling 
 *	and implantation with LEPTON-CMS 1.x
 *
 *	@version	0.1.0
 *	@package	Glossary
 *	@author		Dietrich Roland Pehlke
 *	@notice		Based on some code-snippets from Ralf Hertsch and Frank Heyne
 *
 */

class c_lepton_sec
{
	protected $guid = "9D383EAE-26C0-4C0F-AF13-B3DB994701CE";

	public $max_level = 10;
	public $error_msg = "[ <b>%s</b> ] Can't include class.secure.php!";
	
	protected $look_up = "/framework/class.secure.php";
	protected $one_up = "../";
	
	public function __construct() {

	}
	
	public function __destruct() {
	
	}
	
	public function __call( $name, $arg ) {
	
	}
	
	public function include_secure() {
		$level = 1;
		$root = $this->one_up;
		while( ($level <= $this->max_level) && (!file_exists($root.$this->look_up))) {
			$root .= $this->one_up;
			$level++;
		}
		if(file_exists($root.$this->look_up)) {
			include($root.$this->look_up);
		} else {
			trigger_error(sprintf(
				$this->error_msg,
				$_SERVER['SCRIPT_NAME']
				),
				E_USER_ERROR
			);
   		}	
	}
}

?>