<?php
/*
Plugin Name: Stumble Info Link
Plugin URI: http://edward.de.leau.net/stumble-info-icon-plugin
Description: Active stumble-upon icons for all links, permalinks and widget
Author: Edward de Leau
Author URI: http://edward.de.leau.net
Version: 2.1

License: Copyright 2008,2009,2010  edward de Leau  (email : deleau@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
Installation:
	1. Upload the directory in your plugins directory
	2. Activate the plugin from your WordPress admin 'Plugins' page.
	3. Change the settings in the admin panel
 Issues:
	Can be submitted here: http://code.google.com/p/wp-su-plugin/issues/list
 RSS ISSUE SU fix:
	Please worship QualityChuck here: http://qualitychuck.stumbleupon.com/
	Please worship MattPaul here: http://mattpaul.stumbleupon.com/

*/

require_once(ABSPATH . '/wp-admin/includes/plugin.php');
require_once(ABSPATH . WPINC . '/pluggable.php');
include_once(ABSPATH . WPINC . '/rss.php');
require_once dirname( __FILE__  ) . '/edl-classes/class-EdlHtmlRef.php';
require_once dirname( __FILE__  ) . '/edl-classes/class-EdlStumbleUponButton.php';
require_once dirname( __FILE__  ) . '/edl-classes/class-EdlWPPluginAdmin.php';
require_once dirname( __FILE__  ) . '/edl-classes/class-EdlStumbleUponAdmin.php';
require_once dirname( __FILE__  ) . '/edl-classes/class-EdlStumbleUponButtonStyle.php';
require_once dirname( __FILE__  ) . '/edl-classes/class-EdlStumbleUponWidget.php';

if (class_exists("EdlStumbleUponButton") && class_exists("EdlStumbleUponAdmin")) 
{
	try 
	{
		$su_admin = new EdlStumbleUponAdmin();
		$su_button = new EdlStumbleUponButton();
		$su_button_style = new EdlStumbleUponButtonStyle();
		$su_button_style->Init($su_admin->GetAdminOptions());	
		$su_button->Init($su_admin->GetAdminOptions());		
		$su_button->AddFilter();
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
		exit();
	}
}

/*
 * also provide access to regular functions to insert the button in a template with code
 * (backwards compatible with older versions)
 */ 
function get_the_stumbleInfoButton($strUrl='http://www.google.com', $strTitle='', $strSubmitType='submit', 
	$boolShowReViews=1, $boolShowPageviews=1, $boolShowExtraText=0, $strBefore='(', $strAfter=')', 
	$strLoser='submit', $strWinner='', $boolStyleSheet=0)
{
	$arr_StumbleUpon_Button_Options = array();	
	$arr_StumbleUpon_Button_Options['stumbleinfo_showicon1'] = $boolShowReViews;
	$arr_StumbleUpon_Button_Options['stumbleinfo_showicon2'] = $boolShowPageviews;
	$arr_StumbleUpon_Button_Options['stumbleinfo_showicon3'] = $boolShowExtraText;
	$arr_StumbleUpon_Button_Options['stumbleinfo_beforesu'] = $strBefore;
	$arr_StumbleUpon_Button_Options['stumbleinfo_aftersu'] = $strAfter;
	$arr_StumbleUpon_Button_Options['stumbleinfo_loser'] = $strLoser;
	$arr_StumbleUpon_Button_Options['stumbleinfo_winner'] = $strWinner;
	$arr_StumbleUpon_Button_Options['stumbleinfo_ownstylesheet'] = $boolStyleSheet;

	$su_button = new EdlStumbleUponButton();
	$su_button->Init($arr_StumbleUpon_Button_Options);
	$str_button_code = $su_button->CreateAndGetSuButton($strUrl, $strTitle, $strSubmitType);
	return $str_button_code;
}

function stumbleInfoButton($strUrl='http://www.google.com', $strTitle='', $strSubmitType='submit', 
	$boolShowReViews=1, $boolShowPageviews=1, $boolShowExtraText=0, $strBefore='(', $strAfter=')', 
	$strLoser='submit', $strWinner='', $boolStyleSheet=0) {
	
	$str_button_code = get_the_stumbleInfoButton($strUrl, $strTitle, $strSubmitType, 
		$boolShowReViews, $boolShowPageviews, $boolShowExtraText, $strBefore, $strAfter, 
		$strLoser, $strWinner, $boolStyleSheet);
	
	echo $str_button_code;
}


?>