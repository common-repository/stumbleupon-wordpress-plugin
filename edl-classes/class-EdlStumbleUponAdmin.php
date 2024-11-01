<?php
/*
 * Class: EdlStumbleUponAdmin
 * 
 * Manages and holds the options an end user can set in the Admin Panel for the plugin
 *
 */

if (!class_exists("EdlStumbleUponAdmin") && class_exists("EdlWPPluginAdmin") ) 
{
	class EdlStumbleUponAdmin extends EdlWPPluginAdmin
	{
		static $mStrSpecificPluginOptionsName = 'stumbleinfo_options';
		static $mSpecificPluginSettingsGroup = 'su_settings_group';
		
		static $mStrSpecificPluginAdminPageTitle = 'StumbleInfo Options'; 
		static $mStrSpecificPluginAdminMenuTitle = 'StumbleUpon SEO';
		static $mStrSpecificPluginAdminAccessLevel = 'administrator';
		static $mStrSpecificPluginAdminFile =  'stumbleinfo';
	
		/*
		* constructor
		*/
		public function __construct() 
		{
			parent::SetPluginOptionsName(self::$mStrSpecificPluginOptionsName);
			parent::SetPluginSettingsGroup(self::$mSpecificPluginSettingsGroup);
			parent::RegisterPluginSettings();
			
			$arr_stumbleinfo_options = array();
			$arr_stumbleinfo_options['stumbleinfo_badge'] = 1;
			$arr_stumbleinfo_options['stumbleinfo_showicon1'] = 0;
			$arr_stumbleinfo_options['stumbleinfo_showicon2'] = 0;
			$arr_stumbleinfo_options['stumbleinfo_showicon3'] = 0;
			$arr_stumbleinfo_options['stumbleinfo_button'] = 1;
			$arr_stumbleinfo_options['stumbleinfo_beforesu'] = '(';
			$arr_stumbleinfo_options['stumbleinfo_aftersu'] = ')';
			$arr_stumbleinfo_options['stumbleinfo_loser'] = 'please stumble this';
			$arr_stumbleinfo_options['stumbleinfo_winner'] = 'supersite';
			$arr_stumbleinfo_options['stumbleinfo_onlybutton'] = 0;
			$arr_stumbleinfo_options['stumbleinfo_ownstylesheet'] = 0;
			
			parent::SetPluginOptions($arr_stumbleinfo_options);
			
			parent::SetOptionsPage(self::$mStrSpecificPluginAdminPageTitle, 
				self::$mStrSpecificPluginAdminMenuTitle,
				self::$mStrSpecificPluginAdminAccessLevel, 
				self::$mStrSpecificPluginAdminFile);
			
			parent::AddOptionMenu();
					
		}
		function EdlStumbleUponAdmin()
		{
			return $this->__construct();
		}		
		
		function AdminFormContent() {
			$str_form_content = "";
			$str_form_content .= '<h2>StumbleUpon SEO information Icon</h2>';
			
			$str_form_content .= '<h3> 1. Reviews / Pageviews Icon</h3>';
			$str_form_content .= '<table>';
			$str_form_content .= parent::CreateFieldCheckbox('stumbleinfo_showicon1', 'Show Reviews', '');
			$str_form_content .= parent::CreateFieldCheckbox('stumbleinfo_showicon2', 'Show Pageviews', '');
			$str_form_content .= parent::CreateFieldRadio('stumbleinfo_button', 'Buttons for white background', '', '', '1');
			$str_form_content .= parent::CreateFieldRadio('stumbleinfo_button', 'Buttons for black background', '', '', '2');
			$str_form_content .= '</table>';
	
			$str_form_content .= '<h3> 2. Amount of Reviews in Text</h3>';
			$str_form_content .= '<p><i>Select this if you want some additional text.</p></i>';
			$str_form_content .= '<table>';
			$str_form_content .= parent::CreateFieldCheckbox('stumbleinfo_showicon3', 'Show pageviews (text)', '');
			$str_form_content .= parent::CreateFieldText('stumbleinfo_beforesu', 'Text before the indicator', 'e.g. "  ("');
			$str_form_content .= parent::CreateFieldText('stumbleinfo_aftersu', 'Text after the indicator', 'e.g. " reviews)"');
			$str_form_content .= parent::CreateFieldText('stumbleinfo_loser', 'Text for sites not in su yet', 'e.g. "please stumble this!"');
			$str_form_content .= parent::CreateFieldText('stumbleinfo_winner', 'Text for super sites', 'e.g. "supersite!"');
			$str_form_content .= '</table>';

			$str_form_content .= '<hr />';
			$str_form_content .= '<p>You can also use either the widget (in your Appearance Settings) or even just echo the individual buttons (if you know what you are doing):</p>';
			$str_form_content .= '<code>stumbleInfoButton(get_permalink(),get_the_title(),\'submit\',';
			$str_form_content .= 'Show Reviews [1|0], Show Pageviews [1|0], Show Extra Text [1|0],';
			$str_form_content .= '\'Text Before Count\', \'Text After Count\', \'Text for sites not in SU\', \'Winner Sites Text\', Use Own StyleSheet [1|0]);</code>';
			$str_form_content .= 'or use <code>get_the_stumbleInfoButton()</code> with the same parameters to get the buttoncode for further processing.';
			$str_form_content .= '<hr />';

			$str_form_content .= '<h3> Your own stylesheet </h3>';
			$str_form_content .= '<p>';
			$str_form_content .= 'If you want your own stylesheet to format buttons/text and/or use your own buttons, then ';
			$str_form_content .= 'just choose to use your own stylesheet. *warning* : if you tick this one and do not have ';
			$str_form_content .= 'your own styling in place, the links will look like a mess!';
			$str_form_content .= '</p>';
			$str_form_content .= '<table>';
			$str_form_content .= parent::CreateFieldCheckbox('stumbleinfo_ownstylesheet', 'Use my own stylesheet!', '');
			$str_form_content .= '</table>';

			$str_form_content .= '<p>';
			$str_form_content .= '<a href="http://edward.de.leau.net/stumble-info-icon-plugin" target="_blank">Main homepage of the plugin</a>.<br />';
			$str_form_content .= '<a href="http://code.google.com/p/wp-su-plugin/issues/list" target="_blank">Report issues here</a>.<br />';
			$str_form_content .= '<a href="http://edward.de.leau.net">weblog of author: Edward de Leau</a><br />';
			$str_form_content .= '</p>';

			return $str_form_content;
		}	
		
	}	
}

?>