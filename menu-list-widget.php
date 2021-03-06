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
		echo '<div class="menu-list-widget">';
		$this->_generate_menu_list();
		echo '</div>';
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
		$title = strip_tags($instance['title']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<?php
	}

	function _generate_menu_list() {
		$page_id = get_the_ID();
		if (get_post_type($page_id) == "page") { // current post is a page
			$ancestors = get_ancestors($page_id, "page");
			if (count($ancestors) > 0) // not toplevel item
				$top_id = end(array_values($ancestors)); // get toplevel item
			else
				$top_id = $page_id; // current post is toplevel

			// active pages are pages from the current page up to root
			$active_pages = $ancestors;
			array_push($active_pages, $page_id);

			$this->_walk_children($top_id, $active_pages);
		} else { // show all toplevel pages for other types of posts
			$this->_walk_children(0, array(), 0);
		}
	}

	/** Display children of post with $page_id in the form of unordered list
	 */
	function _walk_children($page_id, $active_pages, $maxdepth = 1, $depth = 0) {
		$children = get_pages(array(
			'post_status' => 'publish',
			'sort_order' => 'asc',
			'sort_column' => 'menu_order',
			'parent' => $page_id
		));
		if (count($children) > 0) {
			echo "<ul>";
			foreach ($children as $child) {
				$is_active = in_array($child->ID, $active_pages);
				echo $is_active ? '<li class="active">' : '<li>';
				echo $this->_format_link($child, $is_active);
				if ($depth < $maxdepth)
					$this->_walk_children($child->ID, $active_pages, $maxdepth, $depth+1);
				echo "</li>";
			}
			echo "</ul>";
		}
	}

	function _format_link($page, $is_active = false) {
		$html = '<a href="' . get_permalink($page->ID) . '" ';
		if ($is_active)
			$html .= 'class="active"';
		$html .= '>' . $page->post_title . '</a>';
		return $html;
	}
}

wp_enqueue_style("menu-list-widget", plugins_url('menu-list-widget/menu-list-widget.css'));
add_action('widgets_init', create_function('', 'return register_widget("MenuListWidget");'));
