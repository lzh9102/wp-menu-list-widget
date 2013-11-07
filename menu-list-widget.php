<?php
/*
Plugin Name: Menu List Widget
Plugin URI: 
Description: A widget containing links to pages arranged as an unordered list
Author: Che-Huai Lin
Version: 1.0
Author URI: http://lzh9102.github.io
 */

class MenuListWidget extends WP_Widget {

	function MenuListWidget() {
		$widget_ops = array('classname' => 'MenuListWidget', 'description' => 'Display menu as unordered list');
		$this->WP_Widget('MenuListWidget', 'Menu List', $widget_ops, $control_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		echo $before_widget;
		if (!empty($title))
			echo $before_title . $title . $after_title;

		echo "<p>Menu List</p>";

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
		$title = strip_tags($instance['title']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<?php
	}
}

add_action('widgets_init', create_function('', 'return register_widget("MenuListWidget");'));
