<?php
	
	class CommonFunctionsHelper extends AppHelper {
			
		function niceUrl($url) {
			return preg_replace("/[^0-9a-zA-Z-]/", "", str_replace(' ', '-', $url));
   		 }
		
	}

?>