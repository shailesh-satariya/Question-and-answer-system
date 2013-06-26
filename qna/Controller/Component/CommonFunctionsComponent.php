<?php
	class CommonFunctionsComponent extends Component  {
		
		/**
		 * Import the CommonFunctions vendor files and instantiate the object.
		 */
		public function __construct() {
			
			/**
			 * Import the CommonFunctions vendor files.
			 */
			App::import('Vendor', 'commonFunctions/commonFunctions');
		}
		
		/**
	   * modify a Text string as URL
	   *
	   * @param string $textInput
	   * @return string URL formatted
	   */
		public function niceUrl($textInput) {
			return rewriteUrl($textInput);
		}
	}
?>