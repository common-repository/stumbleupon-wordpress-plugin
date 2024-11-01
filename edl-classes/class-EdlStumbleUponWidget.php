<?php

if (!class_exists("EdlStumbleUponWidget")) 
{
	class EdlStumbleUponWidget extends WP_Widget 
	{

		/*
  		 * constructor
		 */
		public function __construct() 
		{
			$arrWidgetOptions = array('classname' => 'class_stumbleupon_button', 
				'description' => 'Shows a StumbleUpon button');
			$this->WP_Widget('edlsuwidget', 'StumbleUponButton',  $arrWidgetOptions);			
		}	
		function EdlStumbleUponWidget()
		{
			return $this->__construct();
		}
		
		function widget($args, $instance)
		{
			extract($args);
			$title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']); 
			
			echo $before_widget;
			if (!empty($title))
			{ 
				echo $before_title.$title.$after_title;
			} 
			
			//self::show_su_button($instance);
						
			echo $after_widget;
		}
		
		// when widget control form is posted
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags(stripslashes($new_instance['title']));
			return $instance;
		}
		
		// display widget control form
		function form($instance) {

			$instance = wp_parse_args( (array) $instance, array( 'title' => ' ') );
			$title = htmlspecialchars($instance['title']);
		
			?><p><label for="<?php echo $this->get_field_id('title'); ?>">
			Title: <input class="whatever" id="<?php echo $this->get_field_id('title');?>" 
			name="<?php echo $this->get_field_name('title'); ?>" type="text" 
			value="<?php echo attribute_escape($title); ?>" /></label></p><?php

		}
		
		// specific su button code
		function ShowSuButton($instance) 
		{
			echo "I temporarily disabled the widget because it gave me a Allowed memory size of 268435456" .
				"bytes exhausted (tried to allocate 261900 bytes)";
		}
		
	}


function su_register_subutton_widgets()
{
	register_widget('EdlStumbleUponWidget');
} 
//add_action('init', 'su_register_subutton_widgets', 1 );

}
?>