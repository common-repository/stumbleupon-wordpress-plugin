<?php
/*
 * Class: EdlStumbleUponButton
 * 
 *
 */
if (!class_exists("EdlStumbleUponButton") && class_exists("EdlHtmlHref")) 
{
	class EdlStumbleUponButton extends EdlHtmlHref
	{
		const SU_VIEW_PAGE = 'view';	
		const SU_VIEW_PAGE_HTML = 'url/';
		const SU_SUBMIT_PAGE = 'submit';
		const SU_SUBMIT_PAGE_HTML = 'submit?url=';
		const SU_CSS_CLASS_OFF = 'wpsulink wpsulink_off';
		const SU_CSS_CLASS_ON = 'wpsulink wpsulink_on';
		const SU_CSS_CLASS_SUPER = 'wpsulink wpsulink_super';
		const SU_CSS_CLASS_NONE = 'wpsulink_none';
		const SU_BADGE_URI = "http://www.stumbleupon.com/hostedbadge.php?s=1&r=";
		const SU_BADGE_URI2 = "http://www.stumbleupon.com/badge/embed/1/?url=";
		
		const SU_REVIEWS_URI = "http://www.stumbleupon.com/reviews.php?url=";
		const SU_REVIEWS_URI2 = "http://rss.stumbleupon.com/url/";
		const SU_BADGE_URI_PAGEVIEWS = "#<span>(.*?)<\\\/span>#i";
		const SU_MAX_REVIEWS = 9;
		const SU_MIN_REVIEWS = -1;
		const SU_TITLE = 'su'; 
		private $mArrStumbleUponButtonOptions = array();
		
		// set DEBUG to 1 for now to debug
		// :TODO add more debug levels for more kinds of tests
		public $mDebugLevel = 0;
		
		/*
  		 * constructor
		 */
		public function __construct() 
		{
		}
		function EdlStumbleUponButton()
		{
			return $this->__construct();
		}
		
		public function SetDebugLevel($boolDebugLevel) {
			$this->mDebugLevel = $boolDebugLevel;	
			if ($this->mDebugLevel != 1):			
				echo "*** debug level is now off<br />";
			else:
				echo "*** debug level is now on<br />";
			endif;
		}
		
		/*
		 *
		 * gets the variables and apply the filter
		 *
		 */
		public function Init($arrStumbleUponButtonOptions = array()) {
			if ($this->mDebugLevel==1):
				$this->mArrStumbleUponButtonOptions['stumbleinfo_badge'] = 1;
				$this->mArrStumbleUponButtonOptions['stumbleinfo_showicon1'] = 1;
				$this->mArrStumbleUponButtonOptions['stumbleinfo_showicon2'] = 1;
				$this->mArrStumbleUponButtonOptions['stumbleinfo_showicon3'] = 1;
				$this->mArrStumbleUponButtonOptions['stumbleinfo_button'] = 1;
				$this->mArrStumbleUponButtonOptions['stumbleinfo_beforesu'] = '(';
				$this->mArrStumbleUponButtonOptions['stumbleinfo_aftersu'] = ')';
				$this->mArrStumbleUponButtonOptions['stumbleinfo_loser'] = 'please stumble this';
				$this->mArrStumbleUponButtonOptions['stumbleinfo_winner'] = 'supersite';
				$this->mArrStumbleUponButtonOptions['stumbleinfo_onlybutton'] = 0;
				$this->mArrStumbleUponButtonOptions['stumbleinfo_ownstylesheet'] = 0;
				echo "*** debug variables initialized to level 1<br />";
			else:		
				$this->mArrStumbleUponButtonOptions = $arrStumbleUponButtonOptions;
			endif;
			
	
		}
			
		/*
		 *
		 *
		 */
		public function AddFilter() {
				if ($this->mArrStumbleUponButtonOptions['stumbleinfo_showicon1'] ||
				$this->mArrStumbleUponButtonOptions['stumbleinfo_showicon2'] ||
				$this->mArrStumbleUponButtonOptions['stumbleinfo_showicon3'])
			{
				add_filter('the_content', array($this,'ReplaceAll'), 9);
			}		
		}
		
		/*
		 *
		 */
		private function GetAmountOfReviews($strHtmlUri) {
			$str_feed_uri;
			$xml_rss;
	
			// if WP functionality is not available
			if (!function_exists(fetch_rss)) {
				function fetch_rss() { } 
			}
			
			if ($this->mDebugLevel == 1):
				return 7;				
			else:				
				$str_feed_uri = self::SU_REVIEWS_URI . $strHtmlUri;
				$xml_rss = fetch_rss("$str_feed_uri");
				if ($xml_rss->channel['title']):
					return count($xml_rss->items);
				else:
					return self::SU_MIN_REVIEWS;
				endif;						
			endif;
		}
		
		/*
		 *
		 * Get the amount of SU pageviews
		 *
		 */
		function GetAmountOfPageviews($strHtmlUri)
		{	
				// variables used in this method
				$str_su_pageviews;
				$arr_su_response;
				$str_su_response_body;
		
				// if WP functionality is not available
				if (!function_exists(wp_remote_get)) {
					function wp_remote_get() { } 
				}
		
				if ($this->mDebugLevel == 1):
					return 345;					
				else:
					// use the wp_remote_get function to get a response array containing the StumbleUpon
					// badge code which contains the amount of pageviews between a span
					// e.g.: http://www.stumbleupon.com/badge/embed/1/?url=http://nu.nl/
					$arr_su_response = wp_remote_get(self::SU_BADGE_URI2 . $strHtmlUri , array('timeout' => 60));	
					$str_su_response_body = $arr_su_response['body'];										
					preg_match("#<span>(.*?)<\/span>#i", $str_su_response_body , $matches);	
					
					// this is a workaround to strip off the regex tags I didnt know how to do this otherwise
					$str_su_pageviews = str_replace('<\/span>','',$matches[0]);
					$str_su_pageviews = str_replace('<span>','',$str_su_pageviews);
				endif;
				
				return $str_su_pageviews;
		}
		
		/*
		 *
		 */
		function GetCssStyle($boolShowButton = true, $int_amount_of_reviews = 0)
		{
			// variables used in this method
			$su_css_class;
			
			// if it is a button add css styles otherwise use the "none" style
			if ($boolShowButton != false) 
			{
				if ($int_amount_of_reviews == self::SU_MIN_REVIEWS) 
				{
					$su_css_class = self::SU_CSS_CLASS_OFF;
				} 
				else if ($int_amount_of_reviews > self::SU_MAX_REVIEWS) 
				{
					$su_css_class = self::SU_CSS_CLASS_SUPER;
				} 
				else 
				{
					$su_css_class = self::SU_CSS_CLASS_ON;
				}
			} 
			else 
			{
				$su_css_class=self::SU_CSS_CLASS_NONE;
			}
			return $su_css_class;
		}
		
		/*
		 *
		 */
		function GetSubmitTypeInteral($amountOfPageviews) 
		{
			$str_html_submit_type;
		
			if ($amountOfPageviews > 0):
				$str_html_submit_type = self::SU_VIEW_PAGE_HTML;
			else:
				$str_html_submit_type = self::SU_SUBMIT_PAGE_HTML;
			endif;			
			
			return $str_html_submit_type;
		}
		
		/*
		 *
		 */
		function GetSubmitTypeExternal($strUrlType) 
		{
			$str_html_submit_type;
		
			if ($strUrlType==self::SU_VIEW_PAGE)
			{
				$str_html_submit_type = self::SU_VIEW_PAGE_HTML;
			} 
			else if ($strUrlType==self::SU_SUBMIT_PAGE) 
			{
				$str_html_submit_type = self::SU_SUBMIT_PAGE_HTML;
			} 
			else 
			{
				$str_html_submit_type = self::SU_SUBMIT_PAGE_HTML;
			}
			
			return $str_html_submit_type;
		}
		
		/*
		 * creates and returns the HTML text to be added after the href
		 */
		function GetTextHtml($intAmountOfReviews, $intAmountOfPageviews,
			$boolShowReviews, $boolShowPageviews, $strHtmlUri ) 
		{
			$str_su_text_string="";
			
			// REVIEWS TEXT: add one of 3 additional texts
			$str_su_text_string  .= ' <a class="' . $this->GetCssStyle(false,$intAmountOfReviews) 
				. '" href="http://www.stumbleupon.com/' . $this->GetSubmitTypeInteral($intAmountOfReviews)
				. $strHtmlUri . '" target="_blank"><span>';
			
			if ($intAmountOfReviews == self::SU_MIN_REVIEWS):
				$str_su_text_string .= $this->mArrStumbleUponButtonOptions['stumbleinfo_loser'];
			elseif ($intAmountOfReviews > self::SU_MAX_REVIEWS):
				$str_su_text_string .= $this->mArrStumbleUponButtonOptions['stumbleinfo_winner'];
			else:
				$str_su_text_string .= $this->mArrStumbleUponButtonOptions['stumbleinfo_beforesu'];
				if ($boolShowReviews!=false) 
				{
					$str_su_text_string .= $intAmountOfReviews;
				}	
				if ($boolShowReviews!=false && $boolShowPageviews!=false)
				{
					$str_su_text_string .= '/';
				}
				if ($boolShowPageviews!=false) 
				{
					$str_su_text_string .= $intAmountOfPageviews;
				} 
				$str_su_text_string .= $this->mArrStumbleUponButtonOptions['stumbleinfo_aftersu'];
			endif;	
			$str_su_text_string .= '</span></a>';
			
			return $str_su_text_string;
		}
		
		/*
		 * creates and returns the Icon HTML to be added after the href
		 */
		function GetIconHTML($boolShowButton, $intAmountOfReviews, $intAmountOfPageviews,
			$boolShowReviews, $boolShowPageviews, $strHtmlUri)
		{
			$str_su_icon_string = '';
		
			$str_su_icon_string  .= ' <a class="' . $this->GetCssStyle($boolShowButton,$intAmountOfReviews) 
				. '" href="http://www.stumbleupon.com/' . $this->GetSubmitTypeInteral($intAmountOfReviews)
				. $strHtmlUri . '" target="_blank"><span>';
			
			if ($intAmountOfReviews > self::SU_MAX_REVIEWS) 
			{
				$str_su_icon_string .= '&nbsp;&nbsp;&nbsp; ' . $intAmountOfPageviews . '</span></a>';
			} 
			elseif ($intAmountOfReviews == self::SU_MIN_REVIEWS) 
			{
				$str_su_icon_string .= $intAmountOfPageviews . '</span></a>';
			} 
			else 
			{		
				if ($boolShowReviews!=false) 
				{
					$str_su_icon_string .= $intAmountOfReviews;
				}	
				if ($boolShowReviews!=false && $boolShowPageviews!=false)
				{
					$str_su_icon_string .= '/';
				}
				if ($boolShowPageviews!=false) 
				{
					$str_su_icon_string .= $intAmountOfPageviews;
				}
				$str_su_icon_string .=  '</span></a>';
			}
			return $str_su_icon_string;
		}
		
		/*
		 *
		 */
		function CreateAndGetSuButton($strHtmlUri, $strTitle = self::SU_TITLE, $strUrlType = self::SU_VIEW_PAGE) 
		{
			// variables used in this method
			$int_amount_of_reviews;
			$int_amount_of_pageviews;
			$str_su_icon_string;
			$str_su_text_string;
			$bool_show_button;
			$bool_show_pageviews;
			$bool_show_reviews;
	 
			// init variables
			$int_amount_of_reviews=0;
			$int_amount_of_pageviews=0;
			$str_su_icon_string="";
			$str_su_text_string="";
			$bool_show_button=true;
			$bool_show_pageviews=true;
			$bool_show_reviews=true;		
			
			// define some bools to make the code clearer
			$bool_show_button = ($this->mArrStumbleUponButtonOptions['stumbleinfo_showicon1']==1 || 
				$this->mArrStumbleUponButtonOptions['stumbleinfo_showicon2']==1) ? true : false;
			$bool_show_pageviews =($this->mArrStumbleUponButtonOptions['stumbleinfo_showicon2']==1) ? true : false;			
			$bool_show_reviews = ($this->mArrStumbleUponButtonOptions['stumbleinfo_showicon1']==1) ? true : false;
			$bool_show_text = ($this->mArrStumbleUponButtonOptions['stumbleinfo_showicon3']==1) ? true : false;
			
			// get the amount of reviews & pageviews	 
			$int_amount_of_reviews = $this->GetAmountOfReviews($strHtmlUri);
			$int_amount_of_pageviews = $this->GetAmountOfPageviews($strHtmlUri);			
			
			// Add the icon html code and the text html code
			if ($bool_show_button!=false):
				$str_su_icon_string = $this->GetIconHtml($bool_show_button, $int_amount_of_reviews, 
					$int_amount_of_pageviews, $bool_show_reviews, $bool_show_pageviews, $strHtmlUri);
			endif;	
			if ($bool_show_text!=false):
				$str_su_text_string = $this->GetTextHtml($int_amount_of_reviews, $int_amount_of_pageviews, 
					$bool_show_reviews, $bool_show_pageviews, $strHtmlUri);
		    endif;
			
			return $str_su_icon_string . $str_su_text_string;
		}
		
		/*
		 *
		 */
		public function SpecificHrefAdder($strHtmlUri) 
		{

			return $str_su_button = $this->CreateAndGetSuButton($strHtmlUri,self::SU_TITLE,self::SU_VIEW_PAGE);		
		}
				
	} 
} 

?>