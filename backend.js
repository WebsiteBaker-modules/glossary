/**
 *	@package	WebsiteBaker :: Modules :: Glossary
 *	@author		Dietrich Roland Pehlke (Aldus)
 *	@version	0.1.0 - alpha
 *
 */

function do_job( id, message, job, term_id ) {
	// var WB_URL;
	var ref = document.getElementById("glossary_form_" + id);

	if (ref) {
		switch(job) {
			case '1':	//	modify
				ref.elements['job'].value = "modify";
				ref.setAttribute('action', WB_URL+"/modules/glossary/modify_term.php");
				break;
				
			case '0':	// delete
				if(confirm(message)==false) return 0;
				break;
				
			default:
		
		}
		ref.term_id.value = term_id;
		ref.submit();
	}
}