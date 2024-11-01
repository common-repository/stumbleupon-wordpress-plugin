<?php

if (!class_exists("EdlStumbleUponButtonStyle") ) 
{
	class EdlStumbleUponButtonStyle
	{
		private $mArrStumbleUponButtonOptions = array();
	
		/*
		* constructor
		*/
		public function __construct() 
		{
		}
		function EdlStumbleUponButtonStyle()
		{
			return $this->__construct();
		}
	
		public function Init($arrStumbleUponButtonOptions = array()) {
			$this->mArrStumbleUponButtonOptions = $arrStumbleUponButtonOptions;

			if (!$this->mArrStumbleUponButtonOptions['stumbleinfo_ownstylesheet'])
			{
				add_action('wp_head', array($this,'add_stylesheet'));
			}	
		}
		
		function add_stylesheet() {
			echo "\n\t<style type=\"text/css\">\n";
			echo $this->style_button('off','000');
			echo $this->style_button('on','000');
			echo $this->style_button('super','000');
			echo "</style>\n";
		}
		
		function style_button($type,$color) 
		{
			return "a.wpsulink_" . $type . " {text-decoration: none; font-size:10px; vertical-align: baseline; font-family: tahoma, arial; font-weight: 500; color: #" . $color. ";}\n" .
				"a.wpsulink_" . $type . " span {background: url(" . WP_PLUGIN_URL . '/stumbleupon-wordpress-plugin/img/' . $this->mArrStumbleUponButtonOptions['stumbleinfo_button'] . '/' . $type . ".gif) no-repeat scroll center left; text-indent:17px; display:inline-block;width:70px;}\n";
		}
		
	}
}
?>