<?php
/*
 * Plugin Name: History Manager
 * Version: 3.1.1
 * Plugin URI: http://andrewanimation.biz/plugins/history-manager/
 * Description: History manager is a widget that shows archives, categories, tags, recent posts, and recent comments in a collapsable mode on the sidebar.
 * Author: Andrew Stephens
 * Author URI: http://andrewanimation.biz/
 */

class HistoryManager extends WP_Widget {

	function HistoryManager(){
		$widget_ops = array('classname' => 'widget_history_manager', 'description' => __( "History manager is a widget that shows archives, categories, recent posts, and recent comments in a collapsable mode on the sidebar.") );
		$control_ops = array('width' => 600 );
		$this->WP_Widget('history_manager', __('History Manager'), $widget_ops, $control_ops);
	}

	function widget($args, $instance) {
		
		extract($args);
		
		$widget_title = apply_filters('widget_title', $instance['title']);
		$disp = array($instance['disp0'], $instance['disp1'], $instance['disp2'], $instance['disp3'], $instance['disp4']);
		$titles = array($instance['title0'], $instance['title1'], $instance['title2'], $instance['title3'], $instance['title4'], $instance['title']);
		$limit = $instance['limit'];
		
		
		echo $before_widget;
		echo $before_title.$widget_title.$after_title;
		
		$default = $instance['default'];
		if ($instance['default'] != 0) {
			for ($i=0; $i <= $instance['default']; $i++) {
				if ($instance['disp'.$i] != 1) { $default--; }
			}
		}
				
		if ($instance['use_js']) {
			echo "Click to unfold.\n";
			echo '<div class="history_manager">';
		} else {
			echo '<div class="history_manager_nojs">';
		}

		$category_before = '<div>';
		$category_after = "</div>\n";
		$ctitle_before = '<h3>';
		$ctitle_beforeO = '<h3 class="open">';
		$ctitle_after = '</h3>';
		$list_before = '<ul style="margin-top:0; margin-bottom:0;">';
		$list_after = '</ul>';
		

		if ($disp[0] != 0) {
			echo $category_before;
			echo ($disp[0] == 2) ? $ctitle_beforeO : $ctitle_before;
			echo $titles[0].$ctitle_after;
			echo $list_before;
			wp_get_archives('type=postbypost&limit='.$limit);
			echo $list_after.$category_after;
		}

		if ($disp[1] != 0) {
			echo $category_before;
			echo ($disp[1] == 2) ? $ctitle_beforeO : $ctitle_before;
			echo $titles[1].$ctitle_after;
			echo $list_before;
			$src_length=30; global $wpdb;

			/*$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,
			SUBSTRING(comment_content,1,$src_length) AS com_excerpt
			FROM $wpdb->comments
			LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
			WHERE comment_approved = '1' AND comment_type = '' AND post_password = ''
			ORDER BY comment_date_gmt DESC LIMIT $limit";
			$comments = $wpdb->get_results($sql);*/

			$comments = get_comments(array(
				'number' => $limit
			));

			foreach ($comments as $comment) {
				echo "\n\t<li><a href=\"" . get_permalink($comment->ID) . "#comment-" . $comment->comment_ID  . "\" title=\"on " . $comment->post_title . "\">" . $comment->comment_author . "</a>: " . substr(strip_tags($comment->comment_content), 0, 30) . "...</li>";
			}
			echo $list_after.$category_after;
			
		}

		if ($disp[2] != 0) {
			echo $category_before;
			echo ($disp[2] == 2) ? $ctitle_beforeO : $ctitle_before;
			echo $titles[2].$ctitle_after;
			echo $list_before;
			wp_list_categories('orderby=count&order=desc&number='.$limit.'&show_count=1&title_li=');
			echo $list_after.$category_after;
		}

		if ($disp[3] != 0) {
			echo $category_before;
			echo ($disp[3] == 2) ? $ctitle_beforeO : $ctitle_before;
			echo $titles[3].$ctitle_after;
			echo $list_before;
			$tags = get_tags( array('orderby' => 'count', 'order' => 'DESC', 'number' => $limit) );
			foreach ( $tags as $tag ) {
				echo '<li><a href="' . get_tag_link ($tag->term_id) . '" rel="tag">' . $tag->name . '</a> (' . $tag->count . ')</li>';
			}
			echo $list_after.$category_after;
		}

		if ($disp[4] != 0) {
			echo $category_before;
			echo ($disp[4] == 2) ? $ctitle_beforeO : $ctitle_before;
			echo $titles[4].$ctitle_after;
			echo $list_before;
			wp_get_archives('type=monthly&show_post_count=1&limit='.$limit);
			echo $list_after.$category_after;
		}
		
		//echo "</ul>\n";
		echo '</div>';
		echo $after_widget;
		
	}
	
	function form($instance) {
	
	//	echo '<div style="width:600px;"></div>';

		$defaults = array(
			'title' => 'Post History',
			'use_js' => 1,
			'limit' => 10,
			'disp0' => 1,
			'disp1' => 1,
			'disp2' => 1,
			'disp3' => 1,
			'disp4' => 1,
			'title0' => 'Recent Posts',
			'title1' => 'Recent Comments',
			'title2' => 'Categories',
			'title3' => 'Tags',
			'title4' => 'Archives by Month',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		echo '<p>';
		echo '<label for="'.$this->get_field_id('title').'">Widget Title';
		echo '<input id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.$instance['title'].'" /></label><br />';
		
		$disp = ($instance['use_js'] == 1) ? ' checked="checked"' : '';
		echo '<input type="checkbox" value="1" name="'.$this->get_field_name('use_js').'"'.$disp.' />Use Accordion Display<br />';
		
		echo '<label for="'.$this->get_field_id('limit').'">Number of Items per Heading';
		echo '<input id="'.$this->get_field_id('limit').'" name="'.$this->get_field_name('limit').'" value="'.$instance['limit'].'" style="width: 25px;" /></label><br />';
		
		$this->optionset($instance, 'Recent Posts', '0');
		$this->optionset($instance, 'Recent Comments', '1');
		$this->optionset($instance, 'Categories', '2');
		$this->optionset($instance, 'Tags', '3');
		$this->optionset($instance, 'Archives by Month', '4');
	
	}

	function optionset($instance, $title, $position) {

		/*echo '<p>'.$title.':';
		//$disp = ($instance['disp'.$position] == 1) ? array(' selected="selected"', '') : array('', ' selected="selected"');
		$disp = array();
		$disp[$instance['disp'.$position]] = ' selected="selected"';
		echo '<select name="'.$this->get_field_name('disp'.$position).'">';
		echo '<option value="2"'.$disp[2].'>Display Open</option>';
		echo '<option value="1"'.$disp[1].'>Display Closed</option>';
		echo '<option value="0"'.$disp[0].'>Do Not Display</option>';
		echo '</select><br />';
		echo '<label for="'.$this->get_field_id('title'.$position).'">Title: <input name="'.$this->get_field_name('title'.$position).'" id="'.$this->get_field_id('title'.$position).'" value="'.$instance['title'.$position].'" style="width: 200px;" /></label></p>'."\n";*/

		echo '<p>';
		echo '<label style="width:150px; display:block; float:left;" for="'.$this->get_field_id('disp'.$position).'">'.$title.'</label>';
		$disp = array(); $disp[$instance['disp'.$position]] = ' selected="selected"';
		echo '<select name="'.$this->get_field_name('disp'.$position).'">';
		echo '<option value="2"'.$disp[2].'>Display Open</option>';
		echo '<option value="1"'.$disp[1].'>Display Closed</option>';
		echo '<option value="0"'.$disp[0].'>Do Not Display</option>';
		echo '</select>';
		echo '<label style="margin-left:20px;" for="'.$this->get_field_id('title'.$position).'">Display As: <input name="'.$this->get_field_name('title'.$position).'" id="'.$this->get_field_id('title'.$position).'" value="'.$instance['title'.$position].'" style="width: 200px;" /></label></p>'."\n";


	}
	
	function update($new_instance, $instance) {
	
		$instance['title'] = htmlentities($new_instance['title'], ENT_QUOTES);
		$instance['limit'] = empty($new_instance['limit']) ? 10 : intval($new_instance['limit']);
		$instance['use_js'] = empty($new_instance['use_js']) ? 0 : 1;
		$instance['default'] = empty($new_instance['default']) ? 0 : intval($new_instance['default']);
		
		$instance['disp0'] = $new_instance['disp0'];
		$instance['disp1'] = $new_instance['disp1'];
		$instance['disp2'] = $new_instance['disp2'];
		$instance['disp3'] = $new_instance['disp3'];
		$instance['disp4'] = $new_instance['disp4'];
		
		$instance['title0'] = htmlentities($new_instance['title0'], ENT_QUOTES);
		$instance['title1'] = htmlentities($new_instance['title1'], ENT_QUOTES);
		$instance['title2'] = htmlentities($new_instance['title2'], ENT_QUOTES);
		$instance['title3'] = htmlentities($new_instance['title3'], ENT_QUOTES);
		$instance['title4'] = htmlentities($new_instance['title4'], ENT_QUOTES);
		
		return $instance;
	
	}
	
} //END Class

function HM_Init() { register_widget('HistoryManager'); }
add_action('widgets_init', 'HM_Init');

function history_manager_includes() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('history-manager', '/wp-content/plugins/history-manager/history-manager.js', null, array('jquery'));
	//wp_enqueue_style('history-manager-css', '/wp-content/plugins/history-manager/history-manager.css');
	echo '<link href="'.get_bloginfo('url').'/wp-content/plugins/history-manager/history-manager.css" type="text/css" rel="stylesheet" />';
}
if (!is_admin()) { add_action('wp_print_scripts', 'history_manager_includes'); }

