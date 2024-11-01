<?php
/*
 * abstract class for WordPress Admin classes
 *
 *
 */
if (!class_exists("EdlWPPluginAdmin") ) 
{
	abstract class EdlWPPluginAdmin
	{
		public $mArrPluginOptions = array();
		public $mStrPluginOptionsName;
		public $mPluginSettingsGroup;
		
		public $mStrPluginAdminPageTitle; 
		public $mStrPluginAdminMenuTitle;
		public $mStrPluginAdminAccessLevel;
		public $mStrPluginAdminFile;
		
		/*
		* constructor
		*/
		public function __construct() 
		{
		}
		function EdlWPPluginAdmin()
		{
			return $this->__construct();
		}
		
		function SetOptionsPage($strPluginAdminPageTitle, $strPluginAdminMenuTitle,
			$strPluginAdminAccessLevel, $strPluginAdminFile) 
		{
			$this->mStrPluginAdminPageTitle = $strPluginAdminPageTitle;
			$this->mStrPluginAdminMenuTitle = $strPluginAdminMenuTitle;
			$this->mStrPluginAdminAccessLevel = $strPluginAdminAccessLevel;
			$this->mStrPluginAdminFile =  $strPluginAdminFile;
		}
		
		function AddOptionMenu() {
			// http://lists.automattic.com/pipermail/wp-hackers/2010-May/031802.html
			add_options_page($this->mStrPluginAdminPageTitle, 
				$this->mStrPluginAdminMenuTitle, 
				$this->mStrPluginAdminAccessLevel,  
				$this->mStrPluginAdminFile, 
				array($this,'AdminForm'));
		}
				
		/*
		 *
		 */		
		function SetPluginOptions($arrPluginOptions) {
			if(!is_array($arrPluginOptions)){
				throw new Exception('Invalid parameter type!');
			}
			$this->mArrPluginOptions = $arrPluginOptions;
		}
		
		/*
		 *
		 */
		function SetPluginOptionsName($strPluginOptionsName) {
			if(!is_string($strPluginOptionsName)){
				throw new Exception('Invalid parameter type!');
			}
			$this->mStrPluginOptionsName = $strPluginOptionsName;
		}
		
		/*
		 *
		 */
		function SetPluginSettingsGroup($strPluginSettingsGroup) {
			if(!is_string($strPluginSettingsGroup)){
				throw new Exception('Invalid parameter type!');
			}
			$this->mPluginSettingsGroup = $strPluginSettingsGroup;
		}		
		
		/*
		 * stripslashes
		 *
		 */
		function StripSlashes($value)
		{
			$value = is_array($value) ? array_map('$this->StripSlashes', $value) : stripslashes($value);
			return $value;
		}
	
		/*
		 * Parse Request
		 *
		 */
		function ParseRequest($name, $default=null)
		{
			if (!isset($_REQUEST[$name])) return $default;
			//
			// PHP function: get_magic_quotes_gpc : Gets the current configuration
			// setting of magic quotes gpc
			//
			// Based on the configuration setting return the option name
			//
			if (get_magic_quotes_gpc()) return $this->StripSlashes($_REQUEST[$name]);
			else return $_REQUEST[$name];
		}

		/*
		 * Helper for a text field
		 *
		 */
		function CreateFieldText($name, $label='', $tips='', $attrs='')
		{
			$str_form_content = "";
			if (strpos($attrs, 'size') === false) $attrs .= 'size="30"';
			$str_form_content .= '<tr><td class="label">';
			$str_form_content .= '<label for="' . $this->mStrPluginOptionsName . '[' . $name . ']">' . $label . '</label></td>';
			$str_form_content .= '<td><input type="text" ' . $attrs . ' name="' .$this->mStrPluginOptionsName.'[' . $name . ']" value="' .
				htmlspecialchars($this->mArrPluginOptions[$name]) . '"/>';
			$str_form_content .= ' ' . $tips;
			$str_form_content .= '</td></tr>';
			return $str_form_content;
		}

		/*
		 * Helper for a checkbox field
		 *
		 * :TODO if I set the default for one of these checkboxes to 1 it cant be changed. It will keep
		 * getting the default value (1) probably because we check for "empty" in the GetAdminOptions
		 *
		 */
		function CreateFieldCheckbox($name, $label='', $tips='', $attrs='')
		{
			$str_form_content = "";
			$str_form_content .=  '<tr><td class="label">';
			$str_form_content .=  '<label for="' .$this->mStrPluginOptionsName.'[' . $name . ']">' . $label . '</label></td>';
			$str_form_content .=  '<td><input type="checkbox" ' . $attrs . ' name="' .$this->mStrPluginOptionsName.'[' . $name . ']" value="1" ' .
				($this->mArrPluginOptions[$name] == '1' ? 'checked ' : '') . '/>';				
			$str_form_content .=  ' ' . $tips;
			$str_form_content .=  '</td></tr>';
			return $str_form_content;
		}

		/*
		 * Helper for a radio field
		 *
		 */
		function CreateFieldRadio($name, $label='', $tips='', $attrs='', $value='')
		{
			$str_form_content = "";
			$str_form_content .= '<tr><td class="label">';
			$str_form_content .= '<label for="' .$this->mStrPluginOptionsName.'[' . $name . ']">' . $label . '</label></td>';
			$str_form_content .= '<td><input type="radio" ' . $attrs . ' name="' .$this->mStrPluginOptionsName.'[' . $name . ']" value="' . $value . '" ' .
				($this->mArrPluginOptions[$name] == $value ? '"checked "' : '') . '/>';
			$str_form_content .= ' ' . $tips;
			$str_form_content .= '</td></tr>';
			return $str_form_content;
		}
		
		/*
		 *	register plugin settings
		 */
		function RegisterPluginSettings() 
		{
			//WP: http://codex.wordpress.org/Function_Reference/register_setting
			register_setting($this->mPluginSettingsGroup, $this->mStrPluginOptionsName);	
		}
		
		/*
		 *
		 * get adminoptions
		 * 
		 */
		public function GetAdminOptions()
		{
								
			// WP: http://codex.wordpress.org/Function_Reference/get_option
			// we dont pass a default because then we could not check if we need to add it
			$arr_temp_options = get_option($this->mStrPluginOptionsName);
			
			if (!empty($arr_temp_options)) 
			{
				foreach ($arr_temp_options as $key => $value)
				{
					$this->mArrPluginOptions[$key] = $value;
					//echo "(" . $key . "=" . $value . ")";
				}
				// WP: http://codex.wordpress.org/Function_Reference/update_option
				update_option($this->mStrPluginOptionsName,$this->mArrPluginOptions);
			} 
			else 
			{
				// WP: http://codex.wordpress.org/Function_Reference/add_option
				add_option($this->mStrPluginOptionsName, $this->mArrPluginOptions);
				
				
			}
						
			return $this->mArrPluginOptions;		
		}

		abstract function AdminFormContent();
		
		/*
		 *
		 * AdminForm
		 * 
		 */		
		public function AdminForm() {
			$str_form = "";
			$str_form .= "<div class=\"adminform\">";
			$str_form .= "<form method=\"post\" action=\"options.php\">";
			echo $str_form;
			settings_fields($this->mPluginSettingsGroup); 			
			$ArrPluginOptions = self::getAdminOptions();

			$str_form = "";
			$str_form .= $this->AdminFormContent();			
			$str_form .= "<p class=\"submit\">";
			$str_form .= "<input type=\"submit\" name=\"Submit\" value=\"Save Changes\"></p>";			
			$str_form .= "</form>";
			$str_form .= "</div>";
			echo $str_form;
		}
		
	}
}

?>