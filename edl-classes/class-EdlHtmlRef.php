<?php
/*
 * The following abstract class provides methods to work with HTML Hrefs
 *
 */
if (!class_exists("EdlHtmlHref")) {
	abstract class EdlHtmlHref {
		
		const HTML_OPENING_QUOTE = '<';
		const EXTENSION_SEPERATOR = '.';
		const HTML_REF_REGEX = '/<a(.*?)href="(.*?)"(.*?)>(.*?)<\\/a>/i';
		const NOT_ALLOWED_EXTENSIONS = '.zip .pdf .arj';
		
		abstract public function SpecificHrefAdder($strHtmlUri);
		//abstract protected function SpecificHrefReplacer();
		
		/*
		 * get a URI extension based on a the last letters after a dot
		 */		
		public function GetUriExtension($strHayStack, $strNeedle, $boolLeftInclusive = 0, $boolRightInclusive = 0)
		{
			// overview of all variables used in this method
			$str_uri;
			$str_extension;
		
			if (strrpos($strHayStack, $strNeedle) !== false) 
			{
				$str_uri =  substr($strHayStack, 0, strrpos($strHayStack, $strNeedle) + $boolLeftInclusive);
				$boolRightInclusive = ($boolRightInclusive == 0) ? 1 : 0;
				$str_extension =  substr(strrchr($strHayStack, $strNeedle), $boolRightInclusive);				
				return $str_extension;
			} 
			else
			{
				return false;
			}
		} 
		
		/*
		 * Recreates an original href based on a pattern match
		 *
		 */
		private function RecreateOriginalHref($arrUrlMatches) {
			// overview of all variables used in this method
			$str_original_uri_uri;
			$str_original_uri_display_text;
			$str_original_uri_attributes_before;
			$str_original_uri_attributes_after;
			$str_original_uri_html;
			$str_original_uri_type_extension;
						
			// give the matches some decent names again
			$str_original_uri_attributes_before = $arrUrlMatches[1];
			$str_original_uri_uri = $arrUrlMatches[2];
			$str_original_uri_attributes_after = $arrUrlMatches[3];
			$str_original_uri_display_text = $arrUrlMatches[4];
						
			// recreate the original html reference and store it in a variable
			$str_original_uri_html = 
				'<a' . $str_original_uri_attributes_before . 
				'href="' . $str_original_uri_uri . '"' . 
				$str_original_uri_attributes_after.'>' . 
				$str_original_uri_display_text . 
				'</a>';			
			
			return $str_original_uri_html;
		}
		
		/*
		 * Validate the passed HTML Href
		 *
		 */		
		private function ValidateHTMLHref($arrUrlMatches) {
		
			// overview of all variables used in this method
			$str_original_uri_uri;
			$str_original_uri_display_text;
			$str_original_uri_attributes_before;
			$str_original_uri_attributes_after;
			$str_original_uri_html;
			$bool_href_valid;
			
			// let's start to suppose it is valid (be positive :))
			$bool_href_valid=true;
						
			// give the matches some decent names again
			$str_original_uri_attributes_before = $arrUrlMatches[1];
			$str_original_uri_uri = $arrUrlMatches[2];
			$str_original_uri_attributes_after = $arrUrlMatches[3];
			$str_original_uri_display_text = $arrUrlMatches[4];
		
			if (strstr($str_original_uri_display_text, $this->HTML_OPENING_QUOTE)) 
			{
				$bool_href_valid=false;
			} 
			else 
			// if the string is of any type we do not want to process return the original string
			{
				$str_original_uri_type_extension 
					= self::GetUriExtension($str_original_uri_uri, $this->EXTENSION_SEPERATOR, 1, 1);
				if ($str_original_uri_type_extension != false)
				{
					if (stristr($this->NOT_ALLOWED_EXTENSIONS, $str_original_uri_type_extension) != false) 
					{
						$bool_href_valid=false;
					}
				}				
			}
			return $bool_href_valid;
		}
		
		private function ReplaceHtmlInOneHref($arrUrlMatches) {
			// not implemented yet maybe for a next plugin :)
		}
		 				
		/*
		 *
		 * generic method to add code behind a href
		 *
		 */		 
		private function AddExtraHtmlToOneHref($arrUrlMatches)
		{
			// overview of all variables used in this method
			$str_original_uri_uri;
			$str_original_uri_html;
						
			// give the uri some decent name, not needed but i like it
			$str_original_uri_uri = $arrUrlMatches[2];
						
			// recreate the original html reference and store it so we only need to 
			// call this method once
			$str_original_uri_html = $this->RecreateOriginalHref($arrUrlMatches);				
			
			// if the href is valid add things otherwise dont
			if ($this->ValidateHTMLHref($arrUrlMatches)) {				
				return $str_original_uri_html . $this->SpecificHrefAdder($str_original_uri_uri);				
			} else {
				return $str_original_uri_html;
			}

		}		 
		 
		/*
		 * The public method that can be called to replace hrefs in html
		 *
		 */
		public function ReplaceAll($strHtmlBodyText) 
		{
			$changed_html_reference;	
		
			// replace things in it
			//$changed_html_reference = preg_replace_callback(EdlHtmlHref::HTML_REF_REGEX, 
			//	'EdlHtmlHref::ReplaceHtmlInOneHref', $strHtmlBodyText);
		
			// add things after it
			$changed_html_reference = preg_replace_callback(self::HTML_REF_REGEX, 
				array($this,'AddExtraHtmlToOneHref'), $strHtmlBodyText);
		
			return $changed_html_reference;
		}		 		
	}
}
?>